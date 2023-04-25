<?php

namespace App\Listeners;

use App\Services\Nbn\ShippingAddressService;
use Illuminate\Contracts\Queue\ShouldQueue;

class InternetServiceInfoCreateListener implements ShouldQueue
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
        $handler = new ShippingAddressService($event->applicationId);
        $handler->handle();
    }
}
