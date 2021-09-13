<?php

namespace App\Listeners\HTTP;

use App\Services\HTTPLoggerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Queue\InteractsWithQueue;

class LogRequestReceiving
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
     * @param ResponseReceived $event
     * @return void
     */
    public function handle(ResponseReceived $event)
    {
        info("******************** Received ****************************");
        info(json_encode($event->response->headers()));
        info($event->request->url());
        (new HTTPLoggerService())->update($event);
    }
}
