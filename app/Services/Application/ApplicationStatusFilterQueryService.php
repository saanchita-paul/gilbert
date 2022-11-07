<?php

namespace App\Services\Application;

use App\Models\ApplicationServiceStatus;

/**
 *
 */
class ApplicationStatusFilterQueryService
{
    public function getServiceStatusDD($statuses)
    {
        return ApplicationServiceStatus::where('is_active', true)
            ->where('type', 'service')->whereIn('status_value', $statuses)->get();
    }
}
