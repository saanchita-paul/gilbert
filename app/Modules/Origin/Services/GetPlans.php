<?php

namespace Origin\Services;

use App\Models\OriginPlan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GetPlans
{
    const MAP_FUEL_TYPE = [
        'electricity' => '01',
        'gas' => '02',
        'water' => '03',
    ];

    const MAP_CUSTOMER_TYPE = [
        'resident' => '0001',
        'business' => '0002',
    ];

    /**
     * @return array
     */
    public static function getActivePlans($fuel_type = null, $customer_type = null){
        $query = OriginPlan::where('status', 'ACTIVE');
        // $filtered = [];

        if(!empty($fuel_type) && in_array($fuel_type, array_keys(self::MAP_FUEL_TYPE))){
            $query->where('division_id', self::MAP_FUEL_TYPE[$fuel_type]);
            // $filtered['fuel_type'] = $fuel_type;
        }

        if(!empty($customer_type) && in_array($customer_type, array_keys(self::MAP_CUSTOMER_TYPE))){
            $query->where('customer_type_id', self::MAP_CUSTOMER_TYPE[$customer_type]);
            // $filtered['customer_type'] = $customer_type;
        }

        return $query->get()->toArray();
    }

    /**
     * @return OriginPlan
     */
    public static function getActivePlanByStateFuel($state, $fuel) : OriginPlan {
        $base = config('bot.root_url');
        $endpoint = '/hood-dashboard/api/origin-plan-code';
        $stateParam = 'state=' . $state;
        $url = $base . $endpoint . '?' . $stateParam;

        $headers = [
            "Accept" => "application/json",
        ];

        $response = Http::withOptions([
            "headers" => $headers,
            "verify" => false,
        ])->get($url);

        $response->throw();
        
        $responseData = json_decode($response->body(), true);
        $plans = $responseData['data'];

        $selectedPlan = [];
        
        foreach($plans as $plan){
            if(str_contains(strtolower($plan['fuel_type']), $fuel)){
                $selectedPlan = $plan;
            }
        }

        if(empty($selectedPlan)){
            throw new \Exception(sprintf("Unable to fetch origin plans for production using state = %s and fuel = %s", $state, $fuel));
        }

        $campaign_id = $selectedPlan['campaign_code'];
        $product_code = $selectedPlan['product_id'];
        
        $planDetails = OriginPlan::where('campaign_id', $campaign_id)
                            ->where('product_code', $product_code)
                            ->first();
        if(!$planDetails){
            $newProductInfo = new StoreProductInfoAPI($campaign_id, $product_code);
            $saved = $newProductInfo->fetch();
            $planDetails = OriginPlan::findOrFail($saved['id']);
        }             
        
        return $planDetails;
    }
}
