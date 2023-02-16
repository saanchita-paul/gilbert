<?php

namespace App\Services;

use App\Models\ExternalSource;

class SourceFilterService
{
    public function getSource()
    {
        return ExternalSource::fromCache();
    }
}
