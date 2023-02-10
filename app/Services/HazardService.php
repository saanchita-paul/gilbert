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
    public static function getHazards(): Collection|array
    {
        return Hazard::where('is_active', true)->get([
            'id',
            'title',
            'powershop_value'
        ]);
    }

    /**
     * Check if application has already dog hazard
     *
     * @param $app
     * @return bool
     */
    public static function isDogExists($app): bool
    {
        return (bool)$app->hazards()->where('powershop_value', 'dog')->exists();
    }

    /**
     * Check if application has already renovation hazard
     *
     * @param $app
     * @return bool
     */
    public static function isRenovationExists($app): bool
    {
        return (bool)$app->hazards()->where('powershop_value', 'electrical_safety_issue')->exists();
    }

    /**
     * Migrate old hazard data to new hazard table
     *
     * @return void
     */
    public static function migrateOldData(): void
    {
        ini_set('max_execution_time', 0);
        $apps = ConnectionApplication::query()->select(['id', 'is_any_unrestrained_animal', 'is_renovation_on'])
            ->where('is_any_unrestrained_animal', 1)
            ->orWhere('is_renovation_on', 1)->cursor();

        foreach ($apps as $app) {
            // Set dog hazard
            if ($app->is_any_unrestrained_animal && !self::isDogExists($app)) {
                $app->hazards()->attach(self::getDogHazardId());
            }

            // Set renovation hazard
            if ($app->is_renovation_on && !self::isRenovationExists($app)) {
                $app->hazards()->attach(self::getRenovationHazardId());
            }
        }
    }

    /**
     * Get renovation hazard id
     *
     * @return int
     */
    public static function getRenovationHazardId(): int
    {
        return (int)Hazard::where('powershop_value', 'electrical_safety_issue')->first()->id;
    }

    /**
     * Get dog hazard id
     *
     * @return int
     */
    public static function getDogHazardId(): int
    {
        return (int)Hazard::where('powershop_value', 'dog')->first()->id;
    }
}
