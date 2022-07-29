<?php

namespace App\Http\Controllers;
use App\Models\ConnectionApplication;
use App\Services\PowerShop\CAFGenerationService;
use http\Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class PowerShopController extends Controller
{
    public function generatePowershopCaf(Request $request)
    {
     $ids = explode(',', $request->get('ids'));
        try {
            $service = new CAFGenerationService($ids);
            return $service->downloadCAF();
        }
        catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }
}
