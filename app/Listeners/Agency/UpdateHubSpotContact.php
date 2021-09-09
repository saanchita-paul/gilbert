<?php

namespace App\Listeners\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Services\Agency\HubspotContactService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateHubSpotContact implements ShouldQueue
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
     * @throws \Exception
     */
    public function handle(SubmitApplicationEvent $event)
    {
        $hubspotContactService = new HubspotContactService($event->applicationId);
        $hubspotContactService->update();
    }
}
