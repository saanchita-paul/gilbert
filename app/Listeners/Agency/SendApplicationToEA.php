<?php

namespace App\Listeners\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use App\Services\Sales\PostSalesService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $submitType = $event->submitType;
        $application = ConnectionApplication::with('connectionServices')->where('id', $event->applicationId)->firstOrFail();

        $saleApiOn = env('EA_SALES_API_ON');
        if ($saleApiOn === "1" && $submitType === 'energy' && $this->isValidForSalesApi($application) ) {
            $postEaService = new PostSalesService($event->applicationId);
            $postEaService->postToEa();
            $hubspotService = new HubspotContactService($event->applicationId);
            $hubspotService->update();
        }


    }

    /**
     * @param $application
     * @return bool
     */
    private function isValidForSalesApi($application): bool
    {
        foreach ($application->connectionServices as $service) {
            $provider = $service->provider_name?$service->provider_name:'';
            info('provider name '.$provider);
            if ($service->provider_name === 'ea' && is_null($service->lead_reference)) {
                return true;
            }
        }

        return  false;
    }
}
