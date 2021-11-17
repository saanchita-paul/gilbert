<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
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
//Fox Automatic rejection steps to replicate issue
            SendApplicationToEA::class,
            UpdateHubSpotContact::class,
            SendNotificationToSupportListener::class,
        ],
        CreateApplicationEvent::class => [
            CreateHubSpotContact::class,
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
    }
}
