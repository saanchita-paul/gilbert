<?php

namespace App\Services\ManualStatus;

use App\Models\ManualStatusChangeLog;

class ManualStatusChangeLogService
{
    public static function getLogsByApplicationId($applicationId)
    {
        return ManualStatusChangeLog::where('connection_application_id', $applicationId)->latest()->get();
    }
}
