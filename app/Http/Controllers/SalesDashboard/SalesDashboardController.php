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
            'energy' => $data,
            'water' => []
        ]]);
    }
}
