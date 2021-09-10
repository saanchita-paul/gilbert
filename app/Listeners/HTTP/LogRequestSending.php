<?php

namespace App\Listeners\HTTP;

use App\Services\HTTPLoggerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Queue\InteractsWithQueue;

class LogRequestSending
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
     * @param RequestSending $event
     * @return void
     */
    public function handle(RequestSending $event)
    {
        info("*********************** SEND *************************");
        info($event->request->url());
        info(json_encode($event->request->headers()));
        (new HTTPLoggerService())->create($event->request);
    }
}
