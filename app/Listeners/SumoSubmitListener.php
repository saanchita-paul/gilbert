<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Utility\SumoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ConnectionService;

/**
 *
 */
class SumoSubmitListener implements ShouldQueue
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
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $application = ConnectionApplication::whereId($event->applicationId)->with("connectionServices")->firstOrFail();
        $submitType = $event->submitType;

        $allowedSubmitType = ['energy', 'power', 'gas'];
        if ($this->isProviderSumo($application, $submitType) && in_array($submitType, $allowedSubmitType)) {
            $res = (new SumoService())->storeCustomerData($event->applicationId);
            info("Sumo response body 1");
            \Log::info($res['status']);
            (new SumoService())->saveStatus($event->applicationId, $res['status'] , $res['creditCheck'], $submitType);
            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
            info(json_encode($res));
            info("Sumo response body 2");
        }
    }

    /**
     * @param $application
     * @return bool
     */
    private function isProviderSumo($application, $submitType): bool
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        foreach ($application->connectionServices as $service) {

            if ($service->provider_name === 'sumo' && in_array($service->service_type, $services)) {
                return true;
            }
        }

        return false;
    }
}
