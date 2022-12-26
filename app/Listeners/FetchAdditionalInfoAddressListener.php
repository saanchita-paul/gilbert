<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Agency\MirnNmiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FetchAdditionalInfoAddressListener implements ShouldQueue
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
        $application = ConnectionApplication::find($event->applicationId);
        MirnNmiService::fetchMirnNmi($application->id);
        MirnNmiService::fetchIsEmbedded(null, true, $application->id);
        $application->update(['loading_address_info' => false]);
        event(new \App\Events\TestEvent('Address additional info fetched successfully'));
    }
}
