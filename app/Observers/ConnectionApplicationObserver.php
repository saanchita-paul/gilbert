<?php

namespace App\Observers;

use App\Models\ConnectionApplication;
use App\Services\Agency\TriageFlagService;

class ConnectionApplicationObserver
{
    /**
     * Handle the ConnectionApplication "created" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function created(ConnectionApplication $connectionApplication)
    {
        TriageFlagService::setTriageFlag($connectionApplication->id);
    }

    /**
     * Handle the ConnectionApplication "updated" event.
     *
     * @param ConnectionApplication $application
     * @return bool
     */
    public function updated(ConnectionApplication $application)
    {
        foreach (TriageFlagService::MANDATORY_APP_FIELDS_NOT_HOOD_AI as $field) {
            if ($application->isDirty($field)) {
               return TriageFlagService::setTriageFlag($application->id);
            }
        }
    }

    /**
     * Handle the ConnectionApplication "deleted" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function deleted(ConnectionApplication $connectionApplication)
    {
        //
    }

    /**
     * Handle the ConnectionApplication "restored" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function restored(ConnectionApplication $connectionApplication)
    {
        //
    }

    /**
     * Handle the ConnectionApplication "force deleted" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function forceDeleted(ConnectionApplication $connectionApplication)
    {
        //
    }
}
