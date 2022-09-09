<?php

namespace Powershop\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\TimeZoneService;
use Carbon\Carbon;
use Cmixin\BusinessTime;
use Exception;

class SameDayConnectionService
{
    private int $applicationId;
    private string $submitType;

    public function __construct(int $applicationId, $submitType)
    {
        $this->applicationId = $applicationId;
        $this->submitType = $submitType;
    }

    const MAP_STATE_NSW = 'New South Wales';
    const MAP_STATE_VIC = 'Victoria';
    const MAP_STATE_QLD = 'Queensland';
    const MAP_STATE_SA  = 'South Australia';
    const MAP_STATE_NT  = 'Northern Territory';
    const MAP_STATE_TAS = 'Tasmania';
    const MAP_STATE_ACT = 'Australian Capital Territory';
    const MAP_STATE_WA = 'Western Australia';

    const VIC_TIME_ZONE = 'Australia/Victoria';

    private function stateTime($state)
    {
        return match ($state) {
            self::MAP_STATE_NSW => today(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC))->addHours(13),
            self::MAP_STATE_SA => today(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC))->addHours(13),
            self::MAP_STATE_VIC => today(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC))->addHours(15),
            self::MAP_STATE_QLD => today(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC))->addHours(10)
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
        $gas = $this->validateGas($application, $this->submitType);

        return [
            'electricityOk' => $electricity,
            'gasOk' => !$gas['isInvalid'],
            'gasNote' => $gas['invalidNote'],
        ];
    }

    /**
     *
     * @return string available date
     */
    public function getNextGasConnectionDate() : string {
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $connectionDate = Carbon::parse($application->moving_date)->shiftTimezone(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $availableDate = $this->getNearestAvailableGasDate($application);

        return $availableDate->gt($connectionDate) ? $availableDate->format('Y-m-d') : $connectionDate->format('Y-m-d');
    }

    private function validateElectricity($application)
    {
        $state = $application->state;
        $currentTime = Carbon::now(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $connectionDate = (new Carbon($application->moving_date))->timezone(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $isToday = $connectionDate->isToday();
        $isPast = $connectionDate->isPast();

        if ($state == self::MAP_STATE_ACT || $isPast) return false;

        if (!$isToday) return true;

        try {
            return $currentTime->lt($this->stateTime($state));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

    }

    private function validateGas(ConnectionApplication $application, string $submitType) : array {
        $result = [
            'isInvalid' => false,
            'invalidNote' => '',
        ];

        if ($submitType == ConnectionService::TYPE_ELECTRICITY){
            return $result;
        }

        if ($submitType == ConnectionService::TYPE_GAS){
            $result['isInvalid'] = true;
            $result['invalidNote'] = 'Powershop does not accept gas only submissions. Please select a different retailer';
            return $result;
        }

        if (!in_array($application->state, [self::MAP_STATE_NSW, self::MAP_STATE_VIC])){
            $result['isInvalid'] = true;
            $result['invalidNote'] = 'Powershop does not provide gas connection for state of ' . $application->state;
            return $result;
        }

        $connectionDate = Carbon::parse($application->moving_date)->shiftTimezone(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $availableDate = $this->getNearestAvailableGasDate($application);

        if($availableDate->gt($connectionDate)){
            $result['invalidNote'] = sprintf('Please let customer know that gas will be connected by distributor in %s business days (%s)',
                                        self::MAP_GAS_BUSINESS_DAYS[$application->state],
                                        $this->getNearestAvailableGasDate($application)->format('Y-m-d'),
                                    );
        }

        if($connectionDate->isWeekend() || $connectionDate->isHoliday()){
            $result['isInvalid'] = true;
            $result['invalidNote'] = 'Connection date falls on a holiday. Please select a different connection date.';
        }

        return $result;
    }

    private function getNearestAvailableGasDate(ConnectionApplication $application) : Carbon
    {
        $state = $application->state ?? 'National';

        if (!in_array($state, ['Victoria', 'New South Wales'])){
            throw new Exception(sprintf('Gas connection is not supported in %s for Powershop', $state));
        }

        BusinessTime::enable(Carbon::class);
        Carbon::setHolidaysRegion(self::MAP_STATE_HOLIDAY[$state]);

        $currentDate = Carbon::now(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $availableDate = Carbon::today(TimeZoneService::getTimeZoneArea(self::MAP_STATE_VIC));
        $businessDays = self::MAP_GAS_BUSINESS_DAYS[$state];
        if ($currentDate->isToday() && intval($currentDate->format('H')) >= 12){
            $businessDays += 1;
        }

        for($i=0; $i<$businessDays-1; $i++){
            $availableDate->addDay();
            while($availableDate->isWeekend()){
                $availableDate->addDay();
            }
        }

        return $availableDate;
    }

}
