<?php

namespace Origin\Services;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Cmixin\BusinessTime;
use App\Models\APILog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nette\Utils\Json;
use App\Models\ConnectionService;
use Carbon\CarbonInterface;

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

    /**
     * Assuming check is weekend/holiday is handled in frontend, this function validates same day/next day connection
     *  
     * @return boolean
     * 
     * @throws exception
     */
    public static function isValidElectricityConnection(string $connectionDate, string $nmi, string $state = 'National'){
        BusinessTime::enable(Carbon::class);
        Carbon::setHolidaysRegion(self::MAP_STATE_HOLIDAY[$state]);

        $connectionDate = Carbon::parse($connectionDate)->shiftTimezone(self::MAP_STATE_TIMEZONE[$state]);
        $nowDate = Carbon::now()->setTimezone(self::MAP_STATE_TIMEZONE[$state]); // check localization  

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

            $checkDate = Carbon::today()->addHours(intval($elecDist['sdfi_business']))->shiftTimezone(self::MAP_STATE_TIMEZONE[$state]);
            $pastCutOff = $nowDate->gt($checkDate);

            if($pastCutOff){
                throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_PAST_CUT_OFF', 'Past Cut-Off time ' . $checkDate->format('g A')), BaseOriginAPI::CODE_REJECT);
            }
        }
        else if ($connectionDate->isTomorrow()){
            $checkDate = $elecDist['isStandardSameDay'] ? Carbon::tomorrow()->shiftTimezone(self::MAP_STATE_TIMEZONE[$state]) : Carbon::today()->shiftTimezone(self::MAP_STATE_TIMEZONE[$state]);
            $checkDate->addHours(intval($elecDist['standard']));
            $pastCutOff = $nowDate->gt($checkDate);
            
            if($pastCutOff){
                throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_PAST_CUT_OFF', 'Past Cut-Off time ' . $checkDate->format('g A')), BaseOriginAPI::CODE_REJECT);
            }
        }

        if($connectionDate->isWeekend() || $connectionDate->isHoliday()){
            throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_HOLIDAY', 'Connection date selected is not a business day'), BaseOriginAPI::CODE_REJECT);
        }

        return true;
    }

    public static function isValidGasConnection(string $connectionDate, string $state = 'National'){
        BusinessTime::enable(Carbon::class);
        Carbon::setHolidaysRegion(self::MAP_STATE_HOLIDAY[$state]);

        $connectionDate = Carbon::parse($connectionDate)->shiftTimezone(self::MAP_STATE_TIMEZONE[$state]);
        $availableDate = Carbon::today()->shiftTimezone(self::MAP_STATE_TIMEZONE[$state])->addDays(5); 

        while($availableDate->isWeekend()){
            $availableDate->addDay();
        }

        if($availableDate->gt($connectionDate)){
            throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_PAST_CUT_OFF', 'Connection date must be after 3 business days minimum'), BaseOriginAPI::CODE_REJECT);
        }

        if($connectionDate->isWeekend() || $connectionDate->isHoliday()){
            throw new \Exception(sprintf('Origin:%s - FAILED [%s](%s)', __FUNCTION__, 'ORGN_HOLIDAY', 'Connection date selected is not a business day'), BaseOriginAPI::CODE_REJECT);
        }

        return true;
    }
}
