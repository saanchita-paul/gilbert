<?php

namespace Reporting\Http\Controllers;

use Illuminate\Http\Request;
use Reporting\Services\EnergyReport;
use Reporting\Services\SaleDashboardService;
use function response;

class ReportController
{
    public function home(Request $request)
    {
        $data =  (new EnergyReport(
            $request->get('start'),
            $request->get('end')
        ))->getEnergyReport();

        return response()->json(['data' => [
            'energy' => $data,
            'water' => []
        ]]);
    }
}
