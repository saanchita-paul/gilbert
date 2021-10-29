<?php

namespace App\Listeners\Agency;

use App\Models\ConnectionApplication;
use App\Notifications\NotifyToSupport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationToSupportListener
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
        // SUBMIT_EMAIL
        $connectionApplication =  ConnectionApplication::find( $event->applicationId );
        $emails =  explode( ',', env('SUBMIT_EMAIL') );
        Notification::route( 'mail', $emails )
        ->notify( new NotifyToSupport( $connectionApplication ) );
    }
}
