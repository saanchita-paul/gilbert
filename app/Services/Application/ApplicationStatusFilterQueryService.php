<?php

namespace App\Services\Application;

use App\Models\ApplicationServiceStatus;
use App\Models\ConnectionApplication;

/**
 *
 */
class ApplicationStatusFilterQueryService
{
    public function getServiceStatusDD($statuses)
    {
        return ApplicationServiceStatus::where('type', 'service')->whereIn('status_value', $statuses)->get();
    }
}
