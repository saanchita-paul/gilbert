<?php

namespace App\Listeners\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Jobs\EnergySubmission\OriginSubmissionJob;
use App\Jobs\EnergySubmission\EASubmissionJob;
use App\Jobs\EnergySubmission\SumoSubmissionJob;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Address\AddressModel;
use App\Services\Address\GBGServices;
use App\Services\Agency\HubspotContactService;
use App\Services\Sales\PostSalesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnergySubmitListener
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
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle(SubmitApplicationEvent $event)
    {
        $ca = ConnectionApplication::query()->where('id', $event->applicationId)->firstOrFail();
        $submitType = $event->submitType;
        $connectionServices = $this->getServices($event->applicationId, $submitType);

            if($ca->is_running_submission) {
                throw new \Exception('Submit skipped as another submit is already in progress');
            };
            $ca->update(['is_running_submission' => 1]);

            switch ($connectionServices->provider_name) {
                case "origin":
                    OriginSubmissionJob::dispatch($event->applicationId, $event->submitType);
                    break;
                case "ea":
                    EASubmissionJob::dispatch($event->applicationId, $event->submitType);
                    break;
                case "sumo":
                    SumoSubmissionJob::dispatch($event->applicationId, $event->submitType);
                    break;
            }


    }

    /**
     * @param $application
     * @return bool
     */
    private function getServices($applicationId, $submitType)
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS],
            default => []

        };

        $connectionService = ConnectionService::where('connection_application_id', $applicationId)
            ->whereIn('service_type', $services)
            ->whereNull('lead_reference')
            ->first();

        return $connectionService;
    }
}
