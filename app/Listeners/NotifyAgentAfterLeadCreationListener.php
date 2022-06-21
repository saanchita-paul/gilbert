<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Notifications\NotifyAgentAboutLeadCreation;
use App\Notifications\NotifyToSupport;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
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


        /**
         * @var ConnectionApplication $ca
         **/
        $ca = ConnectionApplication::find( $event->applicationId );

        if(!($ca->office?->should_notify_agent)) {
            Log::info('Agent Email Notification Off Due to Office has not the permission');
            return;
        }
        Log::info('Agent Email Notification sent');


        $data = [
            'id' => $event->applicationId,
            'full_name' => $this->getFullName($ca->first_name, $ca->middle_name, $ca->last_name),
            'full_address' => $ca->address_text,
            'connection_date' => Carbon::parse($ca->moving_date)->format('d/m/Y'),
            'agent_name' => $ca->getAgentName(),
            'agent_office' => $ca->office?->name
        ];
        $agentEmail = $ca->createdBy?->user?->email;
        Notification::route( 'mail', $agentEmail )
            ->notify( new NotifyAgentAboutLeadCreation( $data ) );
    }

    private function getFullName($firstName, $middleName, $lastName):string
    {
        return $firstName.' '. ($middleName?? ''). ' '.$lastName;
    }
}
