<?php

namespace App\Listeners\HTTP;

use App\Services\Logger\HTTPLoggerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Queue\InteractsWithQueue;

class LogRequestSending
{
    /**
     * Handle the event.
     *
     * @param RequestSending $event
     * @return void
     */
    public function handle(RequestSending $event)
    {
        (new HTTPLoggerService())->create($event->request);
    }
}
