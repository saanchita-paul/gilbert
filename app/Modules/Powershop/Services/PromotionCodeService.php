<?php

namespace Powershop\Services;

use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
class PromotionCodeService
{
    const MAP_PLAN_TYPE = [
        ConnectionService::POWER_SHOP_100_PERCENT_CARBON_NEUTRAL => 'HoodPS100%CarbonNeutral',
        ConnectionService::POWER_SHOP_SWITCH_SAVER => 'hoodswitchsaver100',
    ];

    public static function getCode(string $state, string $service_type, string $plan_type = '') : string {
        // TODO: get code from chatbot api and include plan type as parameter to know what plan customer choose
        $code = '';

        if (!empty($plan_type) && array_key_exists($plan_type, self::MAP_PLAN_TYPE)){
            $code = self::MAP_PLAN_TYPE[$plan_type];
        }
        else {
            if ($state == 'Victoria' && $service_type == ConnectionService::TYPE_ELECTRICITY) {
                $code = 'hoodswitchsaver100';
            }
            
            if ($state == 'Victoria' && $service_type == ConnectionService::TYPE_GAS) {
                $code = 'HoodPS100%CarbonNeutral';
            }
    
            if ($state == 'New South Wales' && in_array($service_type, [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])){
                $code = 'HoodPS100%CarbonNeutral';
            }
    
            if (in_array($state, ['Queensland', 'South Australia']) && $service_type == ConnectionService::TYPE_ELECTRICITY){
                $code = 'HoodPS100%CarbonNeutral';            
            }
        }

        return $code;
    }

    public static function getCodeviaAPI(string $service_type, string $plan_name, string $state, string $postcode, string $nmi = null){
        $code = '';
        try {
            $url = config('bot.root_url').'/hood-dashboard/api/power-shop/promo-code';
            $param = [
                'type' => 'search',
                'plan_name' => $plan_name,
                'state' => $state,
                'postcode' => $postcode,
            ];
            if (!empty($nmi)) $param['nmi_prefix'] = substr($nmi, 0, 3); 

            if (config('app.env') == 'local') $response = Http::withOptions(['verify' => false,])->get($url, $param);
            else $response = Http::get($url, $param);
            
            $data = json_decode($response->body(), true);

            if ($service_type == ConnectionService::TYPE_ELECTRICITY)
                $code = $data['electricity'];
            
            if ($service_type == ConnectionService::TYPE_GAS)
                $code = $data['gas'];
             
        } catch (\Exception $e) {
            Log::warning('No promotion code is found', ['message' => $e->getMessage()]);
        }

        return $code;
    }
}
