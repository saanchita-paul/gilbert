<?php

namespace App\Listeners\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Services\Sales\PostSalesService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        \Log::info($event->applicationId);
        \Log::info("APPLICATION_ID");
    }
}
