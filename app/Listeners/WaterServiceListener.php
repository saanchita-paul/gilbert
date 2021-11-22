<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        if( isset( $event->submitType ) && $event->submitType == 'water' ){
            try {
                $service = new SubmitWaterLeadToFastConnect($event->applicationId);
                $result = $service->submitWaterLead();

                $this->saveRef(data_get($result, "info.customer_reference"), $event->applicationId);

                info("Water submit response body");
                info( json_encode( $result ));
                info("Water submit response body");
            } catch (\Exception $exception) {
                \Log::error('Problem in Water Service Lister ');
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
        }
    }

    /**
     * saving fast connect customer ref
     *
     * @param $ref
     * @param $applicationId
     */
    private function saveRef($ref, $applicationId)
    {
        ConnectionApplication::query()
            ->where('id', $applicationId)
            ->update(['fast_connect_customer_reference' => $ref]);
    }
}

