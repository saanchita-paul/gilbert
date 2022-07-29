<?php

namespace App\Http\Controllers;
use App\Models\ConnectionApplication;
use App\Services\DownloadExcel\ExcelFileService;
use http\Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class DownloadExcelController extends Controller
{
    public function downloadExcel(Request $request)
    {
     $ids = explode(',', $request->get('ids'));
        try {
            $excel = new ExcelFileService();
            return FastExcel::data($excel->getExelFileData($ids))->download('file.xlsx');
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }
}
