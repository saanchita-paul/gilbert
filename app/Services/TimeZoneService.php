<?php

namespace App\Services;

use DateTime;
use DateTimeZone;

class TimeZoneService
{
    public static function getTimeZoneInt(string $zone = 'Australia/Melbourne'): int
    {
        try {
            $dateTimeZone = new DateTimeZone($zone);
            $date = new DateTime(null, $dateTimeZone);
            return $dateTimeZone->getOffset($date)/60/60;

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            return env("TIME_ZONE", 10) ?? 10;
        }
    }


}
