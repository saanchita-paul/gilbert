<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SumoSubmitListener implements ShouldQueue
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        \Log::info('calling sumo listener');
        \Log::info($event->applicationId);
        try {
            $connectionApplication = ConnectionApplication::findOrFail($event->applicationId);
            
            $services = $connectionApplication->connectionServices;

            // if($connectionApplication->soruce == ConnectionApplication::statu)

            \Log::info($connectionApplication);
        } catch (\Exception $exception) {
            \Log::error('problem in app/Listeners/SumoSubmitListener.php , in handle method');
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    }
}
