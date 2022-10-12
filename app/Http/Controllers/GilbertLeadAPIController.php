<?php

namespace App\Http\Controllers;


use App\Http\Resources\GilbertLeadResource;
use App\Services\GilbertLead\GetGilbertLeadService;
use App\Services\GilbertLead\UpdateGilbertConnectionService;
use App\Services\GilbertLead\UpdateGilbertEnergyService;
use App\Services\GilbertLead\UpdateGilbertLeadService;
use App\Services\GilbertToCB\ChatbotToGilbertSyncService;
use Exception;
use Illuminate\Http\Request;

class GilbertLeadAPIController extends Controller
{

    public function syncProperty($id, Request $request)
    {
        try {
            $syncProperty = new ChatbotToGilbertSyncService($id, $request->toArray());
            $syncProperty->sync();
            return $this->sendSuccessResponse('success');
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
