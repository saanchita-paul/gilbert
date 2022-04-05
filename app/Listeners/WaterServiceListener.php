<?php
namespace App\Listeners;


use App\Models\ConnectionApplication;
use App\Services\Agency\UpdatedWaterStatus;
use App\Services\Agency\WaterEmailService;
use App\Services\Utility\AddressValidationService;
use FastConnect\Services\SubmitWaterLeadToFastConnect;
use Illuminate\Contracts\Queue\ShouldQueue;

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
            if($ca->is_auto_water_submit){
                info("Auto submit water lead , skipping");
                return false;
            }

            if($ca->state !== 'Victoria') {
                throw new \Exception('Water Service is not available outside Victoria');
            }
            if($ca->tenancy_type === ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
                throw new \Exception('Water Service is not available for Tenancy Type HomeOwner');
            }

            if($ca->is_water_manual_submitting) {
                throw new \Exception('Water submit skipped as manual submit is already in progress');
            }

            $this->validateCAAddress($ca);

            $ca->update(['is_auto_water_submit' => 0]);
            $ca->update(['is_water_manual_submitting' => 1]);

            $service = new SubmitWaterLeadToFastConnect($event->applicationId);
            $result = $service->submitWaterLead();

            ConnectionApplication::saveFasConnectRef($event->applicationId, data_get($result, "info.customer_reference"));

            /**
             * for water submission we are not changing the application status
             */
//            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);


            $statusAssoc = UpdatedWaterStatus::mapFromFCStatus(data_get($result, "products.0.status"));
            if ($statusAssoc) {
                UpdatedWaterStatus::updateStatus($event->applicationId, $statusAssoc['status'], $statusAssoc['reason']);
            }
        }
        } catch (\Exception $exception) {
            ConnectionApplication::where('id', $event->applicationId)->update(['is_auto_water_submit' => 0 ]);

            // Saving Failed reason and set Water status as Failed
            $service->saveRejectionReason($exception->getMessage(), $event->applicationId, 'water');
            $service->setStatusFailed($event->applicationId, 'water');
            $this->application->update(['is_water_manual_submitting' => 0]);

            // $this->sendEmail($exception->getMessage());
            WaterEmailService::sendEmailWhenSubmissionFails($exception->getMessage() , $event->applicationId);
            \Log::error('Water submission failed, WaterAutoSubmitJob, WaterAutoSubmitJob' , [ $exception->getMessage(), $exception->getTraceAsString()]);
            throw new \Exception('Water submission failed: ' . $exception->getMessage());
        }

    }

    private function validateCAAddress(ConnectionApplication $ca)
    {

        $address = [
            'id'=>$ca->id,
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
        info('water variable log' , ['info' => $missingField]);
        if(!empty($missingField)) {
            // call email and
            // WaterEmailService
            info("water address missing info");
            info('water variable log' , ['info2' => $missingField]);
            WaterEmailService::sendInvalidAddressWaterMail($address);

            // throw new \Exception('Water submission failed, Due to address issue');
        }


    }
}

