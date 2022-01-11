<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Utility\SumoService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        if ($this->isProviderSumo($application) && $event->submitType === "energy") {
            $res = (new SumoService())->storeCustomerData($event->applicationId);
            info("Sumo response body");
            info("no response 1");
            \Log::info($res['status']);
            (new SumoService())->saveStatus($event->applicationId, $res['status'] , $res['creditCheck']);
            info(json_encode($res));
            info("no response 2");
            info("Sumo response body");
        }

    }

    /**
     * @param $application
     * @return bool
     */
    private function isProviderSumo($application): bool
    {
        foreach ($application->connectionServices as $service) {
            if ($service->provider_name === 'sumo') {
                return true;
            }
        }

        return false;
    }
}
