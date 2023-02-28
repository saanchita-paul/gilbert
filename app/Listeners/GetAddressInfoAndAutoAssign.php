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
    public string $queue = 'fc-address';
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
        $application = ConnectionApplication::findOrFail($event->applicationId);

        if (!$application->mirn || !$application->nmi) {
            MirnNmiService::dispatchAllService($event->applicationId);
        }

        if (!$application) {
            Log::warning(
                'FetchAdditionalInfoAddressListener: Application not found for id - '
                . $event->applicationId . '!'
            );
            return false;
        }

        try {
            $autoAssignService = new AutoAssignApplicationService();
            $autoAssignService->assignApplication($application);
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            Log::warning($e->getTraceAsString());
        }
    }
}
