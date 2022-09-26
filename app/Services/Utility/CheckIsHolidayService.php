<?php

namespace App\Services\Utility;

use Illuminate\Support\Facades\Http;
use App\Services\Utility\StateMapService;

class CheckIsHolidayService
{
    /**
     * @param string state // 'Vic'
     * @param string date // '2022-09-22'
     * 
     * @return boolean
     */
    public static function validate(string $state, string $date)
    {
        $url = config('bot.root_url') . '/hood-dashboard/api/is-holiday';
        $state = StateMapService::getShortName($state);
        $params = [
            'state' => $state,
            'date' => $date,
        ];

        $options = [
            'verify' => false,
        ];

        $response = Http::withOptions($options)->get($url, $params)->throw();

        $data = json_decode($response->body(), true);
        
        $isHoliday = $data['data']['is_holiday'] ?? false; 

        return $isHoliday;
    }

    public static function getNextBusinessDay(string $state, string $date = null, bool $includeToday = false)
    {
        $url = config('bot.root_url') . '/hood-dashboard/api/next-business-day';
        $state = StateMapService::getShortName($state);

        $params = [
            'state' => $state,
            'today' => $includeToday
        ];

        if (!empty($date)) {
            $params['date'] = $date;
        }

        $options = [
            'verify' => false,
        ];

        $response = Http::withOptions($options)->get($url, $params)->throw();

        $data = json_decode($response->body(), true);
        
        $nextBusinessDay = $data['next_business_day']; 

        return $nextBusinessDay;
    }
}
