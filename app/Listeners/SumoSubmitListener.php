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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        \Log::info('calling sumo listener');
        \Log::info($event->applicationId);
        try {
            $application = ConnectionApplication::whereId($event->applicationId)->with("connectionServices")->firstOrFail();
            info("**********LIST");
            info($this->isProviderSumo($application));
            if ($this->isProviderSumo($application)) {
                $res = (new SumoService())->storeCustomerData($event->applicationId);
                info(json_encode($res));
            }
        } catch (\Exception $exception) {
            \Log::error('problem in app/Listeners/SumoSubmitListener.php , in handle method');
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
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

        return  false;
    }
}
