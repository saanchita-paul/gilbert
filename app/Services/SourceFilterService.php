<?php

namespace App\Services;

use App\Models\ApplicationSource;

class SourceFilterService
{
    public function getSource()
    {
        return ApplicationSource::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
