<?php

namespace App\Services;

use DateTime;
use DateTimeZone;

class TimeZoneService
{
    const MAP_STATE_TIMEZONE = [
        "New South Wales" => 'Australia/NSW',
        "Victoria" => 'Australia/Victoria',
        "Queensland" => 'Australia/Queensland',
        "South Australia" => 'Australia/South',
        "Northern Territory" => 'Australia/North',
        "Tasmania" => 'Australia/Tasmania',
        "Australian Capital Territory" => 'Australia/ACT',
        'Western Australia' => 'Australia/West',
        'National' => 'Australia/Melbourne',
        'Default' => 'Australia/Melbourne',
    ];

    public static function getTimeZoneInt(string $state = '', string $zone = ''): int
    {
        try {
            if (config('app.use_local_timezone'))
                $zone = config('app.local_timezone');
            else {
                if (empty($zone)){
                    if (!empty($state) && in_array($state, self::MAP_STATE_TIMEZONE)){
                        $zone = self::MAP_STATE_TIMEZONE[$state];
                    } else {
                        $zone = self::MAP_STATE_TIMEZONE['Default'];
                    }
                }
            }
            $dateTimeZone = new DateTimeZone($zone);
            $date = new DateTime('now', $dateTimeZone);
            $return = $dateTimeZone->getOffset($date)/60/60;
            return $return;

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            return config('app.local_timezone');
        }
    }


}
