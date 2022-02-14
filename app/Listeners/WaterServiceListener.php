<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Agency\UpdatedWaterStatus;
use App\Services\Agency\WaterEmailService;
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
            $service = new SubmitWaterLeadToFastConnect($event->applicationId);
            $result = $service->submitWaterLead();

            ConnectionApplication::saveFasConnectRef($event->applicationId, data_get($result, "info.customer_reference"));
            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
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
}

