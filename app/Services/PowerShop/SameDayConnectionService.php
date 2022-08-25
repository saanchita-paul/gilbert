<?php

namespace App\Services\PowerShop;

use App\Models\ConnectionApplication;
use App\Services\TimeZoneService;
use Carbon\Carbon;


class SameDayConnectionService
{
    private int $applicationId;

    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;
    }

    const MAP_STATE = [
        "New South Wales" => 'NSW',
        "Victoria" => 'VIC',
        "Queensland" => 'QLD',
        "South Australia" => 'SA',
        "Northern Territory" => 'NT',
        "Tasmania" => 'TAS',
        "Australian Capital Territory" => 'ACT',
        'Western Australia' => 'WA'
    ];

    const MAP_STATE_TIME = [
        "New South Wales" => '13:00:00',
        "Victoria" => '15:00:00',
        "Queensland" => '10:00:00',
        "South Australia" => '13:00:00'
    ];


    public function validateSameDayConnection()
    {
        $application = ConnectionApplication::findOrFail($this->applicationId);

        $electricity = $this->validateElectricity($application);

        return [
            'electricityOk' => $electricity,
        ];
    }

    private function validateElectricity($application)
    {
        // Australia/Victoria

        $currentTime = Carbon::now(TimeZoneService::getTimeZoneInt('Asia/Dhaka'))->toTimeString();
        $connectionDate = $application->moving_date;
        $state = $application->state;
        $isToday = (new Carbon($connectionDate))->isCurrentDay();

        if (!$isToday) return false;

        try {
            return match(self::MAP_STATE[$state]) {
                'VIC',
                'NSW',
                'QLD',
                'SA' => !($currentTime >= self::MAP_STATE_TIME[$state]),
                'ACT' => !self::MAP_STATE[$state] == 'ACT',
                default => true,
            };
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
        return true;
    }



}
