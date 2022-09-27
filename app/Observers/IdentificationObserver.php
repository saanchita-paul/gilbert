<?php

namespace App\Observers;

use App\Models\Identification;
use App\Services\Agency\TriageFlagService;

class IdentificationObserver
{
    /**
     * Handle the Identification "created" event.
     *
     * @param  \App\Models\Identification  $identification
     * @return void
     */
    public function created(Identification $identification)
    {
        TriageFlagService::setTriageFlag($identification->connection_application_id);
    }

    /**
     * Handle the Identification "updated" event.
     *
     * @param  Identification  $identification
     * @return bool
     */
    public function updated(Identification $identification)
    {
        foreach (TriageFlagService::IDENTIFICATION_FIELDS_HOOD_AI as $field) {
            if ($identification->isDirty($field)) {
                return TriageFlagService::setTriageFlag($identification->connection_application_id);
            }
        }
    }

    /**
     * Handle the Identification "deleted" event.
     *
     * @param  \App\Models\Identification  $identification
     * @return void
     */
    public function deleted(Identification $identification)
    {
        //
    }

    /**
     * Handle the Identification "restored" event.
     *
     * @param  \App\Models\Identification  $identification
     * @return void
     */
    public function restored(Identification $identification)
    {
        //
    }

    /**
     * Handle the Identification "force deleted" event.
     *
     * @param  \App\Models\Identification  $identification
     * @return void
     */
    public function forceDeleted(Identification $identification)
    {
        //
    }
}
