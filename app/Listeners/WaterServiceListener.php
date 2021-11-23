<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Agency\UpdatedWaterStatus;
use Illuminate\Queue\InteractsWithQueue;
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
        //
        if (isset($event->submitType) && $event->submitType == 'water') {
            $service = new SubmitWaterLeadToFastConnect($event->applicationId);
            $result = $service->submitWaterLead();

            ConnectionApplication::saveFasConnectRef($event->applicationId, data_get($result, "info.customer_reference"));

            $statusAssoc = UpdatedWaterStatus::mapFromFCStatus(data_get($result, "products.0.status"));
            if ($statusAssoc) {
                UpdatedWaterStatus::updateStatus($event->applicationId, $statusAssoc['status'], $statusAssoc['reason']);
            }
        }
    }
}

