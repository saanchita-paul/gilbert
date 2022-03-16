<?php

namespace Reporting\Http\Controllers;

use function response;
use Illuminate\Http\Request;
use Reporting\Services\WaterReport;
use App\Http\Controllers\Controller;
use Reporting\Services\EnergyReport;
use App\Services\Agency\SimpleTokenService;
use App\Modules\Reporting\Services\ExportSubmissionReport;

class ReportController extends Controller
{
    public function home(Request $request)
    {
        $energy =  (new EnergyReport(
            $request->get('dateType'),
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
                    $request->get('end')
                    )
            )->run();
        } catch (\Exception $exception) {
            return  response([ 'status' => false, 'msg' => 'Unathenticated'] , 401);
        }
    }

    public function getReportAccessToken(){
        $accessToken = (new SimpleTokenService())->getAccessToken();
        return response( [ 'token' => $accessToken , 'status' => true ] , 200);
    }
}
