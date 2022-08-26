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

    const MAP_STATE_NSW = 'New South Wales';
    const MAP_STATE_VIC = 'Victoria';
    const MAP_STATE_QLD = 'Queensland';
    const MAP_STATE_SA  = 'South Australia';
    const MAP_STATE_NT  = 'Northern Territory';
    const MAP_STATE_TAS = 'Tasmania';
    const MAP_STATE_ACT = 'Australian Capital Territory';
    const MAP_STATE_WA = 'Western Australia';

    private function stateTime($state)
    {
        return match ($state) {
            self::MAP_STATE_NSW,
            self::MAP_STATE_SA => today('Australia/Victoria')->addHours(13),
            self::MAP_STATE_VIC => today('Australia/Victoria')->addHours(15),
            self::MAP_STATE_QLD => today('Australia/Victoria')->addHours(10),
            self::MAP_STATE_ACT => today('Australia/Victoria')
        };
    }

    public function validateSameDayConnection()
    {
        $application = ConnectionApplication::findOrFail($this->applicationId);

        $electricity = $this->validateElectricity($application);

        return [
            'isElectricity' => $electricity
        ];
    }

    private function validateElectricity($application)
    {
        $currentTime = Carbon::now(TimeZoneService::getTimeZoneInt('Australia/Victoria'));
        $connectionDate = $application->moving_date;
        $state = $application->state;
        $isToday = (new Carbon($connectionDate))->timezone('Australia/Victoria')->isToday();

        if (!$isToday) return false;

        try {
            return $currentTime->gt($this->stateTime($state));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

    }



}
