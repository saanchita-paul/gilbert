<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Notifications\NotifyAgentAboutLeadCreation;
use App\Notifications\NotifyToSupport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAgentAfterLeadCreationListener
{
    private String $defaultEmail = 'dimuthu.satharasinghe@brc.technology';
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
        $connectionApplication = ConnectionApplication::find( $event->applicationId );
        $emails =  explode( ',', env('SUBMIT_EMAIL') ?? $this->defaultEmail );
        Notification::route( 'mail', $emails )
            ->notify( new NotifyAgentAboutLeadCreation( $connectionApplication ) );
    }
}
