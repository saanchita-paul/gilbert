<?php

namespace App\Listeners\Agency;

use App\Events\Agency\CreateApplicationEvent;
use App\Services\hubspot\HubspotHandlerService;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $handler = new HubspotHandlerService($event->applicationId);
        $handler->handle();
    }

}
