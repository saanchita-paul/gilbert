<?php

namespace App\Http\Controllers\Agency;

use function response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Agency\Report\ExportReaOfficeReport;
use Illuminate\Support\Facades\Log;

class ReaExtractsReportController extends Controller
{
    public function officeReport(Request $request)
    {
        try {
            if ($request->get('reportType') === 'office') {
                return (new ExportReaOfficeReport(
                    $request->get('officeId'),
                    $request->get('reportType'),
                    $request->get('start'),
                    $request->get('end')
                ))->run();
            } else {
                // return (new ExportReaOfficeReport(
                //     $request->get('type'),
                //     $request->get('start'),
                //     $request->get('end')
                // ))->run();
            }
        } catch (\Exception $exception) {
            return  response(['status' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
