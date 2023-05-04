<?php

namespace App\Observers;

use App\Models\ConnectionService;
use App\Models\ClickConversion;

class ConnectionServiceObserver
{
    /**
     * Handle the ConnectionService "created" event.
     *
     * @param  \App\Models\ConnectionService  $connectionService
     * @return void
     */
    public function created(ConnectionService $connectionService)
    {
        //
    }

    /**
     * Handle the ConnectionService "updated" event.
     *
     * @param  \App\Models\ConnectionService  $connectionService
     * @return void
     */
    public function updated(ConnectionService $connectionService)
    {
        $this->setClickConversionDate($connectionService);
    }

    /**
     * Handle the ConnectionService "deleted" event.
     *
     * @param  \App\Models\ConnectionService  $connectionService
     * @return void
     */
    public function deleted(ConnectionService $connectionService)
    {
        //
    }

    /**
     * Handle the ConnectionService "restored" event.
     *
     * @param  \App\Models\ConnectionService  $connectionService
     * @return void
     */
    public function restored(ConnectionService $connectionService)
    {
        //
    }

    /**
     * Handle the ConnectionService "force deleted" event.
     *
     * @param  \App\Models\ConnectionService  $connectionService
     * @return void
     */
    public function forceDeleted(ConnectionService $connectionService)
    {
        //
    }

    private function setClickConversionDate(ConnectionService $connectionService)
    {
        $application = $connectionService->connectionApplication;
        if (
            $connectionService->isDirty('submitted_at') &&
            $application->clickConversion()->exists() &&
            empty($application->clickConversion->conversion_date)
        ) {
            $click = $application->clickConversion;
            $click->converstion_date = $connectionService->submitted_at;
            $click->save();
        }
    }
}
