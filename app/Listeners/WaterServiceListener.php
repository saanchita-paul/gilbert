<?php

namespace App\Listeners;

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

                info( json_encode( $result ));

            } catch (\Exception $exception) {
                \Log::error('Problem in Water Service Lister ');
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
        }
    }
}
