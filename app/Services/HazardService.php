<?php

namespace App\Services;

use App\Models\ConnectionApplication;
use App\Models\Hazard;
use App\Models\HazardConnectionApplication;
use Illuminate\Database\Eloquent\Collection;

class HazardService
{
    /**
     * @return Collection|Hazard[]
     */
    public static function getHazards()
    {
        return Hazard::where('is_active', true)->get([
            'id',
            'title',
            'powershop_value'
        ]);
    }

    public static function isDogExists($app)
    {
        return $app->hazards()->where('powershop_value', 'dog')->exists();
    }

    public static function isRenovationExists($app)
    {
        return $app->hazards()->where('powershop_value', 'electrical_safety_issue')->exists();
    }
}
