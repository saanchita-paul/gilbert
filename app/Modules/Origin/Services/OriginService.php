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
        'water' => '03'
    ];

    public function __construct(private int $applicationId)
    {
    }

    /**
     * @throws exception
     */
    public function storeElectricity(){
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $application->load(['connectionServices', 'authorizedPerson']);
        
        $authorized = $application->authorizedPerson;
        $service = $application->connectionServices()->where([
            ['service_type', ConnectionService::TYPE_ELECTRICITY],
            ['provider_name', 'origin']
        ])->firstOrFail();

        $service_type = self::MAP_SERVICE_TYPE[$service->service_type];
        $service_plan =  'Origin Advantage'; //todo make a mapper to map with actual plan type
        $connection_date = $application->moving_date;
        $plan = OriginPlan::where([
            ['division_id', $service_type],
            ['description', $service_plan]
        ])->firstOrFail();

        $validateBy = 'nmi';
        $nmiValue = $application->nmi;

        if(empty($nmiValue)){
            // skip connection due to no nmi
            Log::error('ORIGIN Submit Service: Fail - Skip due to missing nmi for service id '. $service->id);
            return;
        }

        // 1. validate address
        $validateAddress = new ValidateAddressAPI($validateBy, $nmiValue);
        $response = $validateAddress->fetch();

        if(!$response || $response['validateStatus'] != 'Valid'){
            // skip due to address validation error
            Log::error('Origin Submit Service: Fail - Invalid address for service id '. $service->id);
            $this->saveRejectedStatus($service->id);
            return;
        }

        $addressInfo = $response['addressInfo'];
        $addressID = $response['addressID'];

        // 2. validate fuel availability
        $customerType = $plan->customer_type_id;
        $checkFuel = new CheckFuelAPI($customerType, $addressID);
        $response = $checkFuel->fetch();

        if(!$response){
            $response = [
                'status' => 'fail',
                'message' => 'Fuel for address is not available'
            ];
            // skip due to fuel check failed
            Log::error('ORIGIN Submit service: Fail - Unable to check fuel for address' . $service->id);
            return;
        }

        foreach($response['fuelOffers'] as $fuelOffer){
            if(strtolower($fuelOffer['fuelType']) == $plan->fuel_type && !in_array($fuelOffer['statusCode'], CheckFuelAPI::ELIGIBLE_STATUSES)){
                $response = [
                    'status' => 'fail',
                    'message' => 'Fuel for address is not available: ' . $fuelOffer['errorReason'],
                ];
                // skip due to fuel is not available for address
                Log::error('ORIGIN Submit service: Fail - Fuel is not available for service id ' . $service->id);
                $this->saveRejectedStatus($service->id);
                return; 
            }
        }

        // 3. submit order
        $data = [
            "connection" => 'move',
            "connectionDate" => $connection_date,
            "isExistingCustomer" => false,
            'isEmailBilling' => $application->is_email_billing == 1,
            'nmi_mirn' => $nmiValue,
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
            Log::error('ORIGIN Submit service: Fail - Invalid inputs to submit order for service id ' . $service->id, $errors);
            return;
        }

        $response = $newOrder->submit();

        if(!$response){
            $response = [
                'status' => 'fail',
                'message' => 'Submit order is not available'
            ];
            // skip due to some error when submit handled by SubmitOrderAPI
            Log::error('ORIGIN Submit service: Fail - Unable to submit order for service ' . $service->id);
            $this->saveRejectedStatus($service->id);
            return;
        }

        if(!empty($response['HoodReferenceNumber'])){
            $this->saveSubmittedStatus($service->id, $response['HoodReferenceNumber']);
        } 
        else {
            Log::error('ORIGIN Submit service: Fail - Missing hood reference number for service ' . $service->id);
            $this->saveRejectedStatus($service->id);
        }

        return;
    }

    /**
     * @throws exception
     */
    public function storeGas(){
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $application->load(['connectionServices', 'authorizedPerson']);
        
        $authorized = $application->authorizedPerson;
        $service = $application->connectionServices()->where([
            ['service_type', ConnectionService::TYPE_GAS],
            ['provider_name', 'origin']
        ])->firstOrFail();

        $service_type = self::MAP_SERVICE_TYPE[$service->service_type];
        $service_plan = "Origin Advantage"; //todo make a mapper to map with actual plan type
        $connection_date = $application->moving_date;
        $plan = OriginPlan::where([
            ['division_id', $service_type],
            ['description', $service_plan]
        ])->firstOrFail();

        $validateBy = 'mirn';
        $mirnValue = $application->mirn_checksum;

        if(empty($mirnValue)){
            // skip connection due to no mirn
            Log::error('ORIGIN Submit Service: Fail - Skip due to missing mirn for service id '. $service->id);
            return;
        }

        // 1. validate address
        $validateAddress = new ValidateAddressAPI($validateBy, $mirnValue);
        $response = $validateAddress->fetch();

        if(!$response || $response['validateStatus'] != 'Valid'){
            // skip due to address validation error
            Log::error('Origin Submit Service: Fail - Invalid address for service id '. $service->id);
            $this->saveRejectedStatus($service->id);
            return;
        }

        $addressInfo = $response['addressInfo'];
        $addressID = $response['addressID'];

        // 2. validate fuel availability
        $customerType = $plan->customer_type_id;
        $checkFuel = new CheckFuelAPI($customerType, $addressID);
        $response = $checkFuel->fetch();

        if(!$response){
            $response = [
                'status' => 'fail',
                'message' => 'Fuel for address is not available'
            ];
            // skip due to fuel check failed
            Log::error('ORIGIN Submit service: Fail - Unable to check fuel for address' . $service->id);
            return;
        }

        foreach($response['fuelOffers'] as $fuelOffer){
            if(strtolower($fuelOffer['fuelType']) == $plan->fuel_type && !in_array($fuelOffer['statusCode'], CheckFuelAPI::ELIGIBLE_STATUSES)){
                $response = [
                    'status' => 'fail',
                    'message' => 'Fuel for address is not available: ' . $fuelOffer['errorReason'],
                ];
                // skip due to fuel is not available for address
                Log::error('ORIGIN Submit service: Fail - Fuel is not available for service id ' . $service->id);
                $this->saveRejectedStatus($service->id);
                return; 
            }
        }

        // 3. submit order
        $data = [
            "connection" => 'move',
            "connectionDate" => $connection_date,
            "isExistingCustomer" => false,
            'isEmailBilling' => $application->is_email_billing == 1,
            'nmi_mirn' => $mirnValue,
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
            Log::error('ORIGIN Submit service: Fail - Invalid inputs to submit order for service id ' . $service->id, $errors);
            return;
        }

        $response = $newOrder->submit();

        if(!$response){
            $response = [
                'status' => 'fail',
                'message' => 'Submit order is not available'
            ];
            // skip due to some error when submit handled by SubmitOrderAPI
            Log::error('ORIGIN Submit service: Fail - Unable to submit order for service ' . $service->id);
            $this->saveRejectedStatus($service->id);
            return;
        }

        if(!empty($response['HoodReferenceNumber'])){
            $this->saveSubmittedStatus($service->id, $response['HoodReferenceNumber']);
        } 
        else {
            Log::error('ORIGIN Submit service: Fail - Missing hood/partner reference number for service ' . $service->id);
            $this->saveRejectedStatus($service->id);
        }

        return;
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
