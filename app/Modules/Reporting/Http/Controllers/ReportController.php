<?php

namespace Reporting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reporting\Services\ExportSubmissionReport;
use function response;
use Illuminate\Http\Request;
use Reporting\Services\WaterReport;
use Reporting\Services\EnergyReport;

class ReportController extends Controller
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


    public function submissionReport(Request $request)
    {
        try {
            return (
                new ExportSubmissionReport(
                    $request->get('type'),
                    $request->get('start'),
                    $request->get('end'))
            )->run();
        } catch (\Exception $exception) {
            return  $this->sendErrorResponse($exception);
        }
    }
}
