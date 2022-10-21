<?php

namespace App\Providers;

use App\Events\NotifyAgentAfterLeadCreation;
use App\Listeners\Agency\CreatePlanNoteListener;
use App\Listeners\NotifyAgentAfterLeadCreationListener;
use App\Listeners\Agency\EnergySubmitListener;
use App\Listeners\SumoSubmitListener;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Observers\ConnectionApplicationObserver;
use App\Observers\ConnectionServiceObserver;
use App\Observers\IdentificationObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\WaterServiceListener;
use App\Listeners\HTTP\LogRequestSending;
use App\Listeners\HTTP\LogRequestReceiving;
use App\Events\Agency\CreateApplicationEvent;
use App\Events\Agency\SubmitApplicationEvent;
use App\Listeners\Agency\SendApplicationToEA;
use App\Listeners\Agency\CreateHubSpotContact;
use App\Listeners\Agency\UpdateHubSpotContact;
use App\Listeners\Agency\SendNotificationToSupportListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\OriginSubmitListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        /**
         *
         */
        SubmitApplicationEvent::class => [
////            SendApplicationToEA::class,
////            OriginSubmitListener::class,
////            SumoSubmitListener::class,
            UpdateHubSpotContact::class,
            WaterServiceListener::class,
            EnergySubmitListener::class,
            CreatePlanNoteListener::class,

        ],
        CreateApplicationEvent::class => [
            CreateHubSpotContact::class,
        ],
        NotifyAgentAfterLeadCreation::class => [
            NotifyAgentAfterLeadCreationListener::class,
        ],


        /**
         * API Logging
         */
        'Illuminate\Http\Client\Events\RequestSending' => [
            LogRequestSending::class,
        ],
        'Illuminate\Http\Client\Events\ResponseReceived' => [
            LogRequestReceiving::class,
        ],
        #todo
//        'Illuminate\Http\Client\Events\ConnectionFailed' => [
//            'App\Listeners\LogConnectionFailed',
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        ConnectionApplication::observe(ConnectionApplicationObserver::class);
        Identification::observe(IdentificationObserver::class);
    }
}
