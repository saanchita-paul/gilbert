<?php

namespace App\Http\Controllers\PowerShop;
use App\Http\Controllers\Controller;
use App\Services\PowerShop\CAFGenerationService;
use Illuminate\Http\Request;

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
