<?php

namespace Reporting\Http\Controllers;

use Reporting\Services\EnergyReport;
use Reporting\Services\SaleDashboardService;
use function response;

class ReportController
{
    public function home()
    {
        $data =  (new EnergyReport("2021-12-01", "2021-12-15"))->getEnergyReport();
        return response()->json(['data' => [
            'energy' => $data,
            'water' => []
        ]]);
    }
}
