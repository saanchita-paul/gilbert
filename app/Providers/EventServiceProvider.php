<?php

namespace App\Providers;

use App\Events\Agency\CreateApplicationEvent;
use App\Events\Agency\SubmitApplicationEvent;
use App\Listeners\Agency\CreateHubSpotContact;
use App\Listeners\Agency\UpdateHubSpotContact;
use App\Listeners\Agency\SendApplicationToEA;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        SubmitApplicationEvent::class => [
            SendApplicationToEA::class,
            UpdateHubSpotContact::class
        ],
        CreateApplicationEvent::class => [
            CreateHubSpotContact::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
