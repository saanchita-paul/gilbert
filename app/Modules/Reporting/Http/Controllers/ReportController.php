<?php

namespace Reporting\Http\Controllers;

use function response;
use Illuminate\Http\Request;
use Reporting\Services\WaterReport;
use Reporting\Services\EnergyReport;
use Reporting\Services\SaleDashboardService;

class ReportController
{
    public function home(Request $request)
    {
        $energy =  (new EnergyReport(
            $request->get('start'),
            $request->get('end')
        ))->getEnergyReport();

        $water =  (new WaterReport(
            $request->get('start'),
            $request->get('end')
        ))->getWaterReport();

        return response()->json(['data' => [
            'energy' => $energy,
            'water' => $water,
        ]]);
    }

}
