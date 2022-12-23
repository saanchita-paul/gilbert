<?php

namespace App\Listeners;

use App\Jobs\AutoAssignAppToChatbotJob;
use App\Models\ConnectionApplication;
use Exception;
use Illuminate\Support\Facades\Log;

class AutoAssignG2CBListener
{
    public const ALLOWED_SOURCES = [
        ConnectionApplication::SOURCE_HOOD,
        ConnectionApplication::SOURCE_FOXIE,
        ConnectionApplication::SOURCE_IGNITE,
        ConnectionApplication::SOURCE_OUR_PROPERTY,
        ConnectionApplication::SOURCE_PROPERTY_ME,
        ConnectionApplication::SOURCE_T_APP
    ];

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
     * @param object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $application = ConnectionApplication::find($event->applicationId);
        if (!$application) {
            throw new Exception(
                'AutoAssignG2CBListener: Application not found for id '
                . $event->applicationId
                . ' !'
            );
        }

        if (!in_array($application->source, self::ALLOWED_SOURCES)) {
            Log::info(
                'AutoAssignG2CBListener: Application source '
                . $application->source
                . ' is not allowed for auto assign to chatbot!'
            );
            throw new Exception(
                'AutoAssignG2CBListener: Application source '
                . $application->source
                . ' is not allowed for auto assign to chatbot!'
            );
        }

        AutoAssignAppToChatbotJob::dispatch($application);
    }
}
