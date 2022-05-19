<?php

namespace Origin\Services;

use Exception;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;

use App\Models\ConnectionService;
use App\Models\OriginPlan;
use App\Models\RejectionReason;

class OriginService
{
    const PROVIDER = 'origin';

    const MAP_SERVICE_TYPE = [
        'power' => '01',
        'gas' => '02',
        // 'water' => '03'
    ];

    public function __construct(private int $applicationId)
    {
    }

    /**
     * @throws exception
     */
    public function storeElectricity(){
        $this->initiate('power');
    }

    /**
     * @throws exception
     */
    public function storeGas(){
        $this->initiate('gas');
    }

    private function initiate($type){
        try {
            if(!in_array($type, array_keys(self::MAP_SERVICE_TYPE))){
                throw new \Exception(sprintf('%s:FAILED (Invalid type for lead submission)', self::class));
            }
            
            $application = ConnectionApplication::findOrFail($this->applicationId);
            $application->load(['connectionServices', 'authorizedPerson']);
            
            $authorized = $application->authorizedPerson;
            $service = $application->connectionServices()->where([
                ['service_type', $type],
                ['provider_name', 'origin']
            ])->firstOrFail();
    
            $service_type = self::MAP_SERVICE_TYPE[$service->service_type];
            $service_plan =  'Origin Advantage'; //todo make a mapper to map with actual plan type
            $connection_date = $application->moving_date;
            $plan = OriginPlan::where([
                ['division_id', $service_type],
                ['description', $service_plan]
            ])->firstOrFail();
    
            if($type == 'power'){
                $validateBy = 'nmi';
                $nmi_mirn = $application->nmi;
            }
            else{
                $validateBy = 'mirn';
                $nmi_mirn = $application->mirn_checksum;
            }
            
            if(empty($nmi_mirn)){
                // skip connection due to no nmi
                throw new \Exception(sprintf('%s:FAILED (Skip due to missing nmi/mirn for service id %u)', self::class, $service->id));
            }
    
            // 1. validate address
            $validateAddress = new ValidateAddressAPI($validateBy, $nmi_mirn);
            $response = $validateAddress->fetch();
    
            $addressInfo = $response['addressInfo'];
            $addressID = $response['addressID'];
    
            // 2. validate fuel availability
            $customerType = $plan->customer_type_id;
            $checkFuel = new CheckFuelAPI($customerType, $addressID, $plan->division_id);
            $response = $checkFuel->fetch();
    
            // 3. submit order
            $data = [
                "connection" => 'move',
                "connectionDate" => $connection_date,
                "isExistingCustomer" => false,
                'isEmailBilling' => !empty($application->is_email_billing) ? $application->is_email_billing == 1 : false,
                "isAccessRequirement" => !empty($application->is_access_require) ? $application->is_access_require == 1 : false, 
                "isUnrestrainedAnimal" => !empty($application->is_any_unrestrained_animal) ? $application->is_any_unrestrained_animal == 1 : false, 
                "isLifeSupport" => !empty($application->has_life_support) ? $application->has_life_support == 1 : false, 
                "isLifeSupportGas" => !empty($application->is_gas_life_support) ? $application->is_gas_life_support == 1 : false, 
                "isElectricalWork" => !empty($application->is_renovation_on) ? $application->is_renovation_on == 1 : false, 
                "isEnableMarketing" => !empty($application->is_email_marketing) ? $application->is_email_marketing == 1 : false,
                "additionalAccessInformation" => $application->additional_access_information ?? '',
                'nmi_mirn' => $nmi_mirn,
                "productInfo" => [
                    'productId' => $plan->product_id,
                    'customerTypeId' => $plan->customer_type_id,
                    'divisionId' => $plan->division_id
                ],
                "addressInfo" => [
                    'addressInfo' => $addressInfo,
                    'addressId' => $addressID
                ],
                "residentialCustomerInfo" => [
                    'title' => $application->title,
                    'firstname' => $application->first_name,
                    'lastname' => $application->last_name,
                    'dob' => Carbon::parse($application->dob)->toDateTimeLocalString(),
                    'phone' => $application->phone, // todo
                    'phonetype' => 'mobile', // todo
                    'email' => $application->email,
                ],
            ];
            
            if(!empty($authorized->phone))
            {
                $data['contactPersonInfo'] = [
                    'title' => $authorized->title,
                    'firstname' => $authorized->first_name,
                    'lastname' => $authorized->last_name,
                    'dob' => Carbon::parse($authorized->dob)->toDateTimeLocalString(),
                    'phone' => $authorized->phone, // todo
                    'phonetype' => 'mobile', // todo
                    'email' => $authorized->email,
                ];
            }

            if(!empty($application->concession_card_type)){
                $data['concessionCardInfo'] = [
                    'type' => $application->concession_card_type,
                    'number' => $application->concession_card_number,
                    'startDate' => Carbon::parse($application->concession_start_date)->toDateTimeLocalString(),
                    'endDate' => Carbon::parse($application->concession_end_date)->toDateTimeLocalString()
                ];
            }

            if(!empty($application->inspection_time)){
                $data["appointmentTime"] = $application->inspection_time;
            }
    
            $newOrder = new SubmitOrderAPI($data, $service->id);
            $errors = $newOrder->hasError();
            if($errors){
                // skip due to server invalid input
                Log::error('Invalid inputs to submit order API', $errors);
                throw new \Exception(sprintf('%s:FAILED (Invalid inputs to submit order for service id %u)', self::class, $service->id));
            }
    
            $response = $newOrder->submit();
    
            if(!empty($response['HoodReferenceNumber'])){
                $this->saveSubmittedStatus($service->id, $response['HoodReferenceNumber']);
                return;
            }
        }
        catch (Exception $exception){
            $message = $exception->getMessage();
            if($exception->getCode() == BaseOriginAPI::CODE_REJECT){
                preg_match('/\[([^\)]*)\]/', $message, $codeMatch);
                preg_match('/\(([^\)]*)\)/', $message, $messageMatch);
                $this->saveRejectedStatus($service->id, $codeMatch[1], $messageMatch[1]);
            }
            Log::error($message);
            throw new \Exception($exception->getMessage());
        }
    }

    public static function saveSubmittedStatus($serviceId, $reference)
    {
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_SUBMITTED;
        $service->lead_reference = $reference;
        $service->submitted_at = Carbon::now();
        $service->save();

        $application = $service->connectionApplication;
        $application->status = ConnectionApplication::STATUS_SUBMITTED;
        $application->save();
    }

    public static function saveRejectedStatus($serviceId, $errorCode = '', $errorMessage = '')
    {
        
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_REJECTED;
        $service->rejected_at = Carbon::now();

        $service->save();
        
        if(!empty($errorCode) && !empty($errorMessage)){
            $newRejectReason = new RejectionReason();
            $newRejectReason->connection_service_id = $service->id;
            $newRejectReason->connection_application_id = $service->connection_application_id;
            $newRejectReason->service_type = $service->service_type;
            $newRejectReason->reason_code = $errorCode;
            $newRejectReason->reason_text = $errorMessage;

            $newRejectReason->save();
        }
    }

}
