<?php

namespace Origin\Services;

use Exception;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;

use App\Models\ConnectionService;
use App\Models\OriginPlan;

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
            $checkFuel = new CheckFuelAPI($customerType, $addressID);
            $response = $checkFuel->fetch();
    
            foreach($response['fuelOffers'] as $fuelOffer){
                if(strtolower($fuelOffer['fuelType']) == $plan->fuel_type && !in_array($fuelOffer['statusCode'], CheckFuelAPI::ELIGIBLE_STATUSES)){
                    // skip due to fuel is not available for address
                    throw new \Exception(sprintf('%s:FAILED (Fuel is not available for service id %u due to %s)', self::class, $service->id, $fuelOffer['errorReason']));
                }
            }
    
            // 3. submit order
            $data = [
                "connection" => 'move',
                "connectionDate" => $connection_date,
                "isExistingCustomer" => false,
                'isEmailBilling' => $application->is_email_billing == 1,
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
    
            $newOrder = new SubmitOrderAPI($data);
            $errors = $newOrder->hasError();
            if($errors){
                // skip due to server invalid input
                throw new \Exception(sprintf('%s:FAILED (Invalid inputs to submit order for service id %u)', self::class, $service->id));
            }
    
            $response = $newOrder->submit();
    
            if(!empty($response['HoodReferenceNumber'])){
                $this->saveSubmittedStatus($service->id, $response['HoodReferenceNumber']);
                return;
            }
        }
        catch (Exception $exception){
            $this->saveRejectedStatus($service->id);
            Log::error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    private function saveSubmittedStatus($serviceId, $reference)
    {
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_SUBMITTED;
        $service->lead_reference = $reference;
        $service->submitted_at = Carbon::now();
        $service->save();

        ConnectionApplication::where('id', $this->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
    }

    private function saveRejectedStatus($serviceId)
    {
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_REJECTED;
        $service->rejected_at = Carbon::now();

        return $service->save();
    }

}
