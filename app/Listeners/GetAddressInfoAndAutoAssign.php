<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Agency\AutoAssignApplicationService;
use App\Services\Agency\MirnNmiService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GetAddressInfoAndAutoAssign implements ShouldQueue
{
    public const ALLOWED_SOURCES = [
        ConnectionApplication::SOURCE_HOOD,
        ConnectionApplication::SOURCE_FOXIE,
        ConnectionApplication::SOURCE_IGNITE,
        ConnectionApplication::SOURCE_OUR_PROPERTY,
        ConnectionApplication::SOURCE_PROPERTY_ME,
        ConnectionApplication::SOURCE_T_APP
    ];
    public $queue = 'fast-connect';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return false
     * @throws Exception
     */
    public function handle($event)
    {
        $application = MirnNmiService::dispatchAllService($event->applicationId);

        if (!$application) {
            Log::warning(
                'FetchAdditionalInfoAddressListener: Application not found for id - '
                . $event->applicationId . '!'
            );
            return false;
        }

        if (!in_array($application->source, self::ALLOWED_SOURCES)) {
            Log::warning(
                'AutoAssignG2CBListener: Application source '
                . $application->source
                . ' is not allowed for auto assign to chatbot!'
            );
            return false;
        }

        try {
            $autoAssignService = new AutoAssignApplicationService();
            $autoAssignService->assignApplication($application);

            Log::info('Auto assign application to chatbot successfully.');
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            Log::warning($e->getTraceAsString());
        }
    }
}
