<?php
namespace App\Listeners\HTTP;

use App\Services\Logger\HTTPLoggerService;
use Illuminate\Http\Client\Events\ResponseReceived;

class LogRequestReceiving
{
    /**
     * Handle the event.
     *
     * @param ResponseReceived $event
     * @return void
     */
    public function handle(ResponseReceived $event)
    {
        (new HTTPLoggerService())->update($event);
    }
}
