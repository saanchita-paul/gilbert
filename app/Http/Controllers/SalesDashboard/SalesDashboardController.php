<?php

namespace App\Http\Controllers\SalesDashboard;

use App\Models\ConnectionService;
use App\Services\SalesDashboard\SaleDashboardService;

class SalesDashboardController
{
    public function home()
    {
        $data =  (new SaleDashboardService("2021-12-02", "2021-12-02"))->run();
        return response()->json(['data' => [
            'energy' => [
                "submission" => [
                    'total' => 100,
                    'ea_power' => 10,
                    'sumo_power' => 2,
                    'sumo_gas' => 2,
                    'ea_gas' => 2,
                    "no_frills" => 12,
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
