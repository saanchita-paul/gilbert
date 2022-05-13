<?php

namespace App\Listeners;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Origin\Services\OriginService;

class OriginSubmitListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle(SubmitApplicationEvent $event)
    {
        $submitType = $event->submitType;

        if (($submitType === 'energy' || $submitType === 'power' || $submitType === 'gas')
            && $this->isValidForOrigin($event->applicationId, $submitType))
        {
            // $originService = new OriginService();
            // match ($submitType) {
            //     'energy' => $originService->storeElectricity($event->applicationId) && $originService->storeGas($event->applicationId),
            //     'power' => $originService->storeElectricity($event->applicationId),
            //     'gas' => $originService->storeGas($event->applicationId),
            // };
            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
        } else {
            info("Skipping Origin Submit", [
                'submit_type' => $submitType,
                'is_services_valid' => $this->isValidForOrigin($event->applicationId, $submitType)
            ]);
        }
    }

    /**
     * @param $application
     * @return bool
     */
    private function isValidForOrigin($applicationId, $submitType): bool
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        $connectionService = ConnectionService::where('connection_application_id', $applicationId)
            ->whereIn('service_type', $services)
            ->where('provider_name' , '=', 'origin')
            ->whereNotNull('plan_type')
            ->first();

        if ($connectionService) {
            return true;
        }
        return  false;
    }
}
