<?php

namespace Origin\Services;

use Exception;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;
use App\Services\Address\AddressModel;

use App\Models\ConnectionService;
use App\Models\OriginPlan;
use App\Models\RejectionReason;
use App\Models\ConnectionApplicationSecondaryACC;

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

            $connection_date = $application->moving_date;

            if(config('app.env') !== 'production'){
                // local/dev fetch origin plan
                $service_type = self::MAP_SERVICE_TYPE[$service->service_type];
                $service_plan =  $type == 'gas' ? 'Origin Advantage' : 'Origin Basic'; //todo make a mapper to map with actual plan type
                $plan = OriginPlan::where([
                    ['division_id', $service_type],
                    ['description', $service_plan]
                ])->firstOrFail();
                $plan_customer_type_id = $plan->customer_type_id;
                $plan_division_id = $plan->division_id;
                $plan_product_id = $plan->product_id;
            }
            else {
                // production fetch origin plan
                $plan = GetPlans::getActivePlanByStateFuel(strtoupper(AddressModel::MAP_STATES_LONG_TO_SHORT[strtolower($application->state)]), $type == 'power' ? 'electricity': $type);
                $plan_customer_type_id = $plan->customer_type_id;
                $plan_division_id = $plan->division_id;
                $plan_product_id = $plan->product_id;
            }

            if($type == 'power'){
                $validateBy = 'nmi';
                $nmi_mirn = $application->nmi;

                if(empty($nmi_mirn)){
                    throw new \Exception(sprintf('%s:FAILED (Skip due to missing nmi/mirn for service id %u)', self::class, $service->id));
                }

//                $isValidElecCutOff = ValidateCutOffTime::isValidElecConnect($connection_date, $nmi_mirn, $application->state);
            }
            else{
                $validateBy = 'mirn';
                $nmi_mirn = $application->mirn_checksum;

                if(empty($nmi_mirn)){
                    throw new \Exception(sprintf('%s:FAILED (Skip due to missing nmi/mirn for service id %u)', self::class, $service->id));
                }

//                $isValidGasCutOff = ValidateCutOffTime::isValidGasConnect($connection_date, $application->state);
            }

            // 1. validate address
            $validateAddress = new ValidateAddressAPI($validateBy, $nmi_mirn);
            $response = $validateAddress->fetch();

            $addressInfo = $response['addressInfo'];
            $addressID = $response['addressID'];

            // 2. validate fuel availability
            $checkFuel = new CheckFuelAPI($plan_customer_type_id, $addressID, $plan_division_id);
            $response = $checkFuel->fetch();

            // 3. submit order
            $data = [
                "connection" => 'move',
                "connectionDate" => $connection_date,
                "isExistingCustomer" => false,
                'isEmailBilling' => !empty($application->is_email_billing) ? $application->is_email_billing == 1 : false,
                'isCorrespondenceEmail' => !empty($application->is_email_billing) ? $application->is_email_billing == 1 : false,
                // 'isCorrespondenceEmail' => !empty($application->is_correspondence_email) ? $application->is_correspondence_email == 1 : false,
                "isAccessRequirement" => !empty($application->is_access_require) ? $application->is_access_require == 1 : !empty($application->additional_access_information),
                "isUnrestrainedAnimal" => !empty($application->is_any_unrestrained_animal) ? $application->is_any_unrestrained_animal == 1 : false,
                "isLifeSupport" => !empty($application->is_power_life_support) && $type == 'power' ? $application->is_power_life_support == 1 : false,
                "isLifeSupportGas" => !empty($application->is_gas_life_support) && $type == 'gas' ? $application->is_gas_life_support == 1 : false,
                "isElectricalWork" => !empty($application->is_renovation_on) ? $application->is_renovation_on == 1 : false,
                "isEnableMarketing" => !empty($application->is_email_marketing) ? $application->is_email_marketing == 1 : false,
                "additionalAccessInformation" => $application->additional_access_information ?? '',
                'nmi_mirn' => $nmi_mirn,
                "productInfo" => [
                    'productId' => $plan_product_id,
                    'customerTypeId' => $plan_customer_type_id,
                    'divisionId' => $plan_division_id
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
                    'phone' => $application->phone_type == ConnectionApplication::PHONE_TYPE_HOMEPHONE ? $application->homephone : ($application->phone ?? $application->international_phone),
                    'phonetype' => $application->phone_type == ConnectionApplication::PHONE_TYPE_HOMEPHONE ? 'landline' : 'mobile',
                    'email' => $application->email,
                ],
            ];

            if($application->is_billing_same != 1){
                $data['correspondenceAddress'] = [
                    'roomNo' => $application->billing_unit_number ?? '',
                    'roomType' => $application->billing_unit_number ? 'U' : '', // todo: create new column for unit/room type
                    'houseNo' => $application->billing_street_number ?? '',
                    'street' => $application->billing_street_name_only ?? '',
                    'streetType' => $application->billing_street_type ?? '',
                    'city' => $application->billing_city ?? '',
                    'postcode' => $application->billing_postcode ?? '',
                    'region' => $application->billing_state ? strtoupper(AddressModel::MAP_STATES_LONG_TO_SHORT[strtolower($application->billing_state)]) : '',
                ];
            }

            if(!empty($authorized->role))
            {
                $data['contactPersonInfo'] = [
                    'title' => $authorized->title,
                    'firstname' => $authorized->first_name,
                    'lastname' => $authorized->last_name,
                    'dob' => Carbon::parse($authorized->dob)->toDateTimeLocalString(),
                    'phone' => $authorized->phone, // todo
                    // 'phonetype' => 'mobile', // todo
                    'email' => $authorized->email ?? '',
                    'type' => $authorized->role == ConnectionApplicationSecondaryACC::FULLY_AUTHORISED_STATUS ? 'authorized' : 'joint',
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

            if($type == 'power' && !empty($application->inspection_time)){
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

            ConnectionApplication::where('id', $this->applicationId)->update([
                'is_running_submission' => 0,
            ]);

            $message = $exception->getMessage();
            if($exception->getCode() == BaseOriginAPI::CODE_REJECT){
                preg_match('/\[([^\)]*)\]/', $message, $codeMatch);
                preg_match('/\(([^\)]*)\)/', $message, $messageMatch);
                $this->saveRejectedStatus($this->applicationId, $service->id, $codeMatch[1], $messageMatch[1]);
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

    public static function saveRejectedStatus($applicationId, $serviceId, $errorCode = '', $errorMessage = '')
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
