<?php

namespace Reporting\Http\Controllers;

use Reporting\Services\SaleDashboardService;
use function response;

class SalesDashboardController
{
    public function home()
    {
        $data =  (new SaleDashboardService("2021-12-02", "2021-12-02"))->run();
        return response()->json(['data' => [
            'energy' => [
                "submission" => [
                    'total' => 100,
                    'ea_gas_total_plan' => 10,
                    'ea_gas_no_frills' => 10,
                    'ea_gas_basic_plan' => 10,
                    'ea_power_total_plan' => 2,
                    'ea_power_no_frills' => 2,
                    'ea_power_basic_plan' => 2,
                    'sumo_power_freedom' => 2,
                    'sumo_gas_freedom' => 2,
                ],
                "conversion" => [
                    'total' => 100,
                    'ea' => 10,
                    'sumo' => 2,
                    'power' => 33,
                    'gas'
                ]
            ],
            'water' => []
        ]]);
    }
}
