<?php

namespace App\Listeners;

use App\Models\ConnectionApplication;
use App\Services\Utility\SumoService;
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
            info("Sumo response body 1");
            \Log::info($res['status']);
            (new SumoService())->saveStatus($event->applicationId, $res['status'] , $res['creditCheck']);
            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
            info(json_encode($res));
            info("Sumo response body 2");
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
