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

    const GAS_BUSINESS_DAYS = 3;

    /**
     * 
     * @return boolean
     * 
     * @throws exception
     */
    public static function isValidElectricityConnection(string $connectionDate, string $state, string $postcode){
        if(!in_array($state, array_keys(self::MAP_STATE))){
            throw new \Exception(sprintf('Origin:%s - FAILED (invalid state)', __FUNCTION__));
        }
        
        $connectionDate = Carbon::parse($connectionDate);
        $nowDate = Carbon::now(); // check
        $distributor = Cache::rememberForever($state.'_'.$postcode, self::getDistributor($postcode, $state));
        $elecDist = $distributor['electricity'];

        if ($connectionDate->isToday()){
            if($connectionDate->isWeekend()){
                $isAfterStart = !empty($elecDist['sdfi_after_start']) ? Carbon::today()->addHours(intval($elecDist['sdfi_after_start']))->lessThan($nowDate): true;
                $isBeforeEnd = !empty($elecDist['sdfi_after_end']) ? Carbon::today()->addHours(intval($elecDist['sdfi_after_end']))->greaterThan($nowDate) : false;

                return $isAfterStart && $isBeforeEnd;
            }
            else{
                $isBeforeEnd = !empty($elecDist['sdfi_business']) ? Carbon::today()->addHours(intval($elecDist['sdfi_business']))->greaterThan($nowDate) : false;
                return $isBeforeEnd;
            }
        }
        else if ($connectionDate->isTomorrow()){
            $isBeforeEnd = !empty($elecDist['standard']) ? Carbon::today()->addHours(intval($elecDist['standard']))->greaterThan($nowDate) : false;
            return $isBeforeEnd;
        }
        else {
            return true;
        }   
    }

    public static function isValidGasConnection(string $connectionDate){
        BusinessTime::enable(Carbon::class);
        $connectionDate = Carbon::parse($connectionDate);
        $availableDate = Carbon::today()->addBusinessDays(self::GAS_BUSINESS_DAYS);
    }

    public static function getDistributor($postcode, $state){
        $url = config('bot.root_url').'/hood-dashboard/api/origin-plan-details'. '?postcode='. $postcode . '&state=' . $state;
        $headers = [
            "Accept" => "application/json",
        ];

        $response = Http::withOptions([
            'headers' => $headers,
            'verify' => false, // check
        ])->get($url);

        $response->throw();
        
        $responseData = json_decode($response->body(), true);

        $elecDist = $responseData['data']['plans']['electricity']['distributor_name'] ?? '';
        $gasDist = $responseData['data']['plans']['gas']['distributor_name'] ?? '';

        return ['electricity' => $elecDist, 'gas' => $gasDist];
    }
}
