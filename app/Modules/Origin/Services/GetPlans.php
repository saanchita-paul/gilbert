<?php

namespace Origin\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class GetPlans
{
    public const MAP_FUEL_TYPE = [
        ConnectionService::TYPE_ELECTRICITY => '01',
        ConnectionService::TYPE_GAS => '02',
        ConnectionService::TYPE_WATER => '03',
    ];

    public const MAP_CUSTOMER_TYPE = [
        ConnectionApplication::PROPERTY_TYPE_RESIDENTIAL => '0001',
        ConnectionApplication::PROPERTY_TYPE_BUSINESS => '0002',
    ];

    public const AVAILABLE_PLAN_PARAMS = [
        'state',
        'fuel',
        'nmi',
        'mirn',
        'postcode',
        'offer'
    ];

    /**
     * @return array
     */
    public static function getActivePlans(string $fuelType, string $customerType, array $data): array
    {
        $base = config('bot.root_url');
        $endpoint = '/hood-dashboard/api/origin-plan-code';
        $url = $base . $endpoint;
        $params = array_filter($data, fn($key) => in_array($key, self::AVAILABLE_PLAN_PARAMS), ARRAY_FILTER_USE_KEY);
        $headers = [
            "Accept" => "application/json",
        ];

        $response = Http::withOptions([
            "headers" => $headers,
            "verify" => false,
        ])->get($url, $params);

        $response->throw();

        $responseData = json_decode($response->body(), true);
        $plans = $responseData['data'];
        info("GetPlans:getActive Plans list", [
            'plans' => $plans,
            'params' => $params
        ]);

        $selectedPlan = $plans[0];
        $selectedPlan['customer_type_id'] = self::MAP_CUSTOMER_TYPE[$customerType];
        $selectedPlan['division_id'] = self::MAP_FUEL_TYPE[$fuelType];

        return $selectedPlan;
    }
}
