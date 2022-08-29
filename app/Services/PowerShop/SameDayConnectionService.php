<?php

namespace App\Services\PowerShop;

use App\Models\ConnectionApplication;
use App\Services\TimeZoneService;
use Carbon\Carbon;
use Cmixin\BusinessTime;
use Exception;

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
    ];

    const MAP_STATE_HOLIDAY = [
        "New South Wales" => 'au-nsw',
        "Victoria" => 'au-vic',
        "Queensland" => 'au-qld',
        "South Australia" => 'au-sa',
        "Northern Territory" => 'au-nt',
        "Tasmania" => 'au-tas',
        "Australian Capital Territory" => 'au-act',
        'Western Australia' => 'au-wa',
        'National' => 'au-national',
    ];

    const MAP_GAS_BUSINESS_DAYS = [
        'Victoria' => '3',
        'New South Wales' => '5',
    ];


    public function validateSameDayConnection()
    {
        $application = ConnectionApplication::findOrFail($this->applicationId);

        $electricity = $this->validateElectricity($application);
        $gas = $this->validateGas($application);
        $gasNote = '';

        if (!$gas) {
            $gasNote = sprintf('Please let customer know that gas will be connected by distributor in %s business days (%s)',
                self::MAP_GAS_BUSINESS_DAYS[$application->state],
                $this->getNearestAvailableGasDate($application)->format('Y-m-d'),
            );
        }

        return [
            'electricityOk' => $electricity,
            'gasOk' => $gas,
            'gasNote' => $gasNote,
        ];
    }

    /**
     *
     * @return string available date
     */
    public function getNextGasConnectionDate() : string {
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $connectionDate = Carbon::parse($application->moving_date)->shiftTimezone(self::MAP_STATE_TIMEZONE[$application->state]);
        $availableDate = $this->getNearestAvailableGasDate($application);

        return $availableDate->gt($connectionDate) ? $availableDate->format('Y-m-d') : $connectionDate->format('Y-m-d');
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

    private function validateGas(ConnectionApplication $application) {
        $connectionDate = Carbon::parse($application->moving_date)->shiftTimezone(self::MAP_STATE_TIMEZONE[$application->state]);
        $availableDate = $this->getNearestAvailableGasDate($application);

        if($availableDate->gt($connectionDate)){
            return false;
        }

        if($connectionDate->isWeekend() || $connectionDate->isHoliday()){
            return false;
        }

        return true;
    }

    private function getNearestAvailableGasDate(ConnectionApplication $application) : Carbon
    {
        $state = $application->state ?? 'National';

        if (!in_array($state, ['Victoria', 'New South Wales'])){
            throw new Exception(sprintf('Gas connection is not supported in %s for Powershop', $state));
        }

        BusinessTime::enable(Carbon::class);
        Carbon::setHolidaysRegion(self::MAP_STATE_HOLIDAY[$state]);

        $currentDate = Carbon::now(self::MAP_STATE_TIMEZONE[$state]);
        $availableDate = Carbon::today(self::MAP_STATE_TIMEZONE[$state]);
        $businessDays = self::MAP_GAS_BUSINESS_DAYS[$state];
        if ($currentDate->isToday() && intval($currentDate->format('H')) >= 12){
            $businessDays += 1;
        }

        for($i=0; $i<$businessDays; $i++){
            $availableDate->addDay();
            while($availableDate->isWeekend()){
                $availableDate->addDay();
            }
        }

        return $availableDate;
    }

}
