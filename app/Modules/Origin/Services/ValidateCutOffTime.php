<?php

namespace Origin\Services;

use Carbon\Carbon;
use App\Models\ConnectionApplication;
use App\Services\TimeZoneService;
use App\Services\Utility\CheckIsHolidayService;

class ValidateCutOffTime
{
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

    const MAP_ELECTRIC_DISTRIBUTOR = [
        'CitiPower' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '15',
            'isStandardSameDay' => false,
        ],
        'Powercor' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '15',
            'isStandardSameDay' => false,
        ],
        'United Energy' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '17',
            'isStandardSameDay' => false,
        ],
        'Jemena' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '17',
            'isStandardSameDay' => false,
        ],
        'Ausnet' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '17',
            'isStandardSameDay' => true,
        ],
        'Energex' => [
            'standard' => '16',
            'sdfi_business' => '12',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '',
            'isStandardSameDay' => false,
        ],
        'ETSA' => [
            'standard' => '17',
            'sdfi_business' => '14',
            'sdfi_after_start' => '14',
            'sdfi_after_end' => '17',
            'isStandardSameDay' => false,
        ],
        'Ausgrid' => [
            'standard' => '14',
            'sdfi_business' => '14',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '',
            'isStandardSameDay' => false,
        ],
        'Endeavour Energy' => [
            'standard' => '14',
            'sdfi_business' => '16',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '',
            'isStandardSameDay' => false,
        ],
        'Essential Energy' => [
            'standard' => '14',
            'sdfi_business' => '16',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '',
            'isStandardSameDay' => false,
        ],
        'EvoEnergy' => [
            'standard' => '14',
            'sdfi_business' => '',
            'sdfi_after_start' => '',
            'sdfi_after_end' => '',
            'isStandardSameDay' => false,
        ],
    ];

    const MAP_NMI_DISTRIBUTOR = [
        'CitiPower' => [
            '61',
        ],
        'Powercor' => [
            '62',
        ],
        'United Energy' => [
            '64',
        ],
        'Jemena' => [
            '60',
        ],
        'Ausnet' => [
            '63',
        ],
        'Energex' => [
            '31',
            'QB',
        ],
        'ETSA' => [
            '20',
        ],
        'Ausgrid' => [
            '41',
        ],
        'Endeavour Energy' => [
            '43',
        ],
        'Essential Energy' => [
            '40',
            '42',
            '44',
            '45',
        ],
        'EvoEnergy' => [
            '70',
        ],
    ];


    const GAS_BUSINESS_DAYS = 3;

    public static function isValidElecConnect($applicationId){
    
        $existingApplication = ConnectionApplication::find($applicationId);

        $state = $existingApplication->state ?? 'National';
        $connectionDate = $existingApplication->moving_date;
        $nmi = $existingApplication->nmi;

        if (empty($nmi)) return true;

        $connectionDate = Carbon::parse($connectionDate)->shiftTimezone(TimeZoneService::getTimeZoneArea($state));
        $nowDate = Carbon::now(TimeZoneService::getTimeZoneArea($state));
        $nextBusinessDay = CheckIsHolidayService::getNextBusinessDay($state);
        $nextDate = Carbon::parse($nextBusinessDay, TimeZoneService::getTimeZoneArea($state));

        $distributor = '';
        $nmi_check = substr($nmi, 0, 2);
        foreach(self::MAP_NMI_DISTRIBUTOR as $key => $value){
            if(in_array($nmi_check, $value)){
                $distributor = $key;
                break;
            }
        }

        if(empty($distributor)){
            throw new \Exception(sprintf('Origin:%s - FAILED (unable to find distributor to validate cutoff time for NMI %s)', __FUNCTION__, $nmi));
        }

        $elecDist = self::MAP_ELECTRIC_DISTRIBUTOR[$distributor];

        if ($connectionDate->isToday()){
            if(empty($elecDist['sdfi_business'])){
                throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_PAST_CUT_OFF', 'Distributor does not support same day connection'), BaseOriginAPI::CODE_REJECT);
            }

            $checkDate = Carbon::today(TimeZoneService::getTimeZoneArea($state))->addHours(intval($elecDist['sdfi_business']));
            $pastCutOff = $nowDate->gt($checkDate);

            if($pastCutOff){
                return false;
            }
        } else if ($connectionDate->eq($nextDate)){
            $checkDate = $elecDist['isStandardSameDay'] ? Carbon::parse($nextDate->format('Y-m-d'))->shiftTimezone(TimeZoneService::getTimeZoneArea($state)) : Carbon::today(TimeZoneService::getTimeZoneArea($state));
            $checkDate->addHours(intval($elecDist['standard']));
            $pastCutOff = $nowDate->gt($checkDate);

            if ($pastCutOff) {
                return false;
            }
        }

        if($connectionDate->isWeekend() || CheckIsHolidayService::validate($state, $connectionDate->format('Y-m-d'))){
            throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_HOLIDAY', 'Connection date selected is not a business day'), BaseOriginAPI::CODE_REJECT);
        }

        return true;
    }

    public static function isValidGasConnect($applicationId){

        $existingApplication = ConnectionApplication::find($applicationId);

        $state = $existingApplication->state ?? 'National';
        $connectionDate = $existingApplication->moving_date;

        $connectionDate = Carbon::parse($connectionDate)->shiftTimezone(TimeZoneService::getTimeZoneArea($state));
        $availableDate = Carbon::today(TimeZoneService::getTimeZoneArea($state));

        for($i=0; $i<=self::GAS_BUSINESS_DAYS; $i++){
            $availableDate->addDay();
            while($availableDate->isWeekend()){
                $availableDate->addDay();
            }
        }

        if($availableDate->gt($connectionDate)){
            return false;
        }

        if($connectionDate->isWeekend() || $connectionDate->isHoliday()){
            throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_HOLIDAY', 'Connection date selected is not a business day'), BaseOriginAPI::CODE_REJECT);
        }

        return true;
    }

    public static function validateCutOff(int $applicationId) {

        $elec = self::isValidElecConnect($applicationId); // validate cutoff for elec only
        $gas = self::isValidGasConnect($applicationId); // validate cutoff for gas only
        return [
            'isElecOkay' => $elec, //true OR false
            'isGasOkay' => $gas, //true OR false
        ];
    }

    /**
     * @param string moving date
     * @param string state DEFAULT == 'National'
     *
     * @return string available date
     */
    public static function getNextGasConnectionDate(string $movingDate, string $state = 'National') {
        $connectionDate = Carbon::parse($movingDate)->shiftTimezone(TimeZoneService::getTimeZoneArea($state));
        $availableDate = Carbon::today(TimeZoneService::getTimeZoneArea($state));

        for($i=0; $i<=self::GAS_BUSINESS_DAYS; $i++){
            $availableDate->addDay();
            while($availableDate->isWeekend()){
                $availableDate->addDay();
            }
        }

        $selectedDate = $availableDate->gt($connectionDate) ? $availableDate->format('Y-m-d') : $connectionDate->format('Y-m-d');
        $validatedDate = CheckIsHolidayService::getNextBusinessDay($state, $selectedDate, true);
        $newAvailableDate = Carbon::parse($validatedDate, TimeZoneService::getTimeZoneArea($state));

        return $newAvailableDate->format('Y-m-d');
    }
}
