<?php

namespace App\Listeners\Agency;

use App\Services\Sales\PostSalesService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Agency\SubmitApplicationEvent;
use FastConnect\Services\SubmitWaterLeadToFastConnect;

class SendApplicationToEA implements ShouldQueue
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
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle(SubmitApplicationEvent $event)
    {
        $postEaService = new PostSalesService($event->applicationId);
        $postEaService->postToEa();

        //
        info('inside EA service listener');
        if( isset( $event->submitType ) && $event->submitType == 'energy' ){
            try {
                $service = new SubmitWaterLeadToFastConnect($event->applicationId);
                $result = $service->submitWaterLead();
                info( json_encode( $result ));
                
            } catch (\Exception $exception) {
                \Log::error('Problem in EA Service Lister ');
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
        }

    }
}
