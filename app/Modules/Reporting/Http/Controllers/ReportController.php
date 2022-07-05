<?php

namespace Reporting\Http\Controllers;

use function response;
use Illuminate\Http\Request;
use Reporting\Services\WaterReport;
use App\Http\Controllers\Controller;
use Reporting\Services\EnergyReport;
use App\Services\Agency\SimpleTokenService;
use App\Modules\Reporting\Services\ExportEnergySubmissionReport;
use App\Modules\Reporting\Services\ExportWaterSubmissionReport;

class ReportController extends Controller
{
    public function home(Request $request)
    {
        $dateType = $request->get('dateType') ?? 'submitted_date';
        $energy =  (new EnergyReport(
            $dateType,
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
            if($request->get('type') === 'energy') {
                return (new ExportEnergySubmissionReport(
                    $request->get('type'),
                    $request->get('start'),
                    $request->get('end')
                ))->run();
            } else {
                return (new ExportWaterSubmissionReport(
                    $request->get('type'),
                    $request->get('start'),
                    $request->get('end')
                ))->run();
            }
        } catch (\Exception $exception) {
            return  response([ 'status' => false, 'msg' => $exception->getMessage()] , 401);
        }
    }

    public function getReportAccessToken(){
        $accessToken = (new SimpleTokenService())->getAccessToken();
        return response( [ 'token' => $accessToken , 'status' => true ] , 200);
    }
}
