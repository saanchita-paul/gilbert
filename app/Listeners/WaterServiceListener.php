<?php

namespace App\Listeners;

use App\Mail\WaterSumissionFailed;
use App\Services\Utility\AddressValidationService;
use Illuminate\Support\Facades\Mail;
use App\Models\ConnectionApplication;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Agency\WaterEmailService;
use App\Services\Agency\UpdatedWaterStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use FastConnect\Services\SubmitWaterLeadToFastConnect;

class WaterServiceListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        try {
        if (isset($event->submitType) && $event->submitType == 'water') {

            $ca = ConnectionApplication::query()->where('id', $event->applicationId)->firstOrFail();
            if($ca->state !== 'Victoria') {
                throw new \Exception('Water Service is not available outside Victoria');
            }
            if($ca->state === ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
                throw new \Exception('Water Service is not available for Tenancy Type HomeOwner');
            }

            $this->validateCAAddress($ca);

            $service = new SubmitWaterLeadToFastConnect($event->applicationId);
            $result = $service->submitWaterLead();

            ConnectionApplication::saveFasConnectRef($event->applicationId, data_get($result, "info.customer_reference"));

            $statusAssoc = UpdatedWaterStatus::mapFromFCStatus(data_get($result, "products.0.status"));
            if ($statusAssoc) {
                UpdatedWaterStatus::updateStatus($event->applicationId, $statusAssoc['status'], $statusAssoc['reason']);
            }
        }
        } catch (\Exception $exception) {
            // $this->sendEmail($exception->getMessage());
            WaterEmailService::sendEmailWhenSubmissionFails($exception->getMessage() , $event->applicationId);
            info('exception in handle method, WaterAutoSubmitJob' , [ $exception->getTraceAsString() , $exception->getMessage() ]);
            throw new \Exception('Water submission failed, WaterAutoSubmitJob');
        }

    }

    private function validateCAAddress(ConnectionApplication $ca)
    {

        $address = [
            'city'=>$ca->city,
            'postcode'=> $ca->postcode,
            'state'=> $ca->state,
            'street_name'=>$ca->street_name,
            'street_number' => $ca->street_number,
            'tenancy_type' => $ca->tenancy_type,
        ];

        $addressKeys = ['city', 'postcode', 'state', 'street_name', 'street_number', 'tenancy_type'];
        $addressValidationService = new AddressValidationService($address, $addressKeys);
        $missingField = $addressValidationService->validate();

        if(!empty($missingField)) {
            // call email and
//            WaterEmailService
            WaterEmailService::sendInvalidAddressWaterMail();

            throw new \Exception('Water submission failed, Due to address issue');
        }


    }
}

