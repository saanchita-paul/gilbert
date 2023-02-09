<?php

namespace App\Services;

use App\Models\Hazard;
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
}
