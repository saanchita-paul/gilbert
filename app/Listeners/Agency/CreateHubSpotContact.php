<?php

namespace App\Listeners\Agency;

use App\Events\Agency\CreateApplicationEvent;
use App\Services\Agency\HubspotContactService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateHubSpotContact implements ShouldQueue
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
     * @param CreateApplicationEvent $event
     * @return void
     * @throws \Exception
     */
    public function handle(CreateApplicationEvent $event)
    {
        $hubspotContactService = new HubspotContactService($event->applicationId);
        $hubspotContactService->create();
    }
}
