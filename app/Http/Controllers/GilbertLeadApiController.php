<?php

namespace App\Http\Controllers;
use App\Services\GilbertToCB\ChatbotToGilbertSyncService;
use Exception;
use Illuminate\Http\Request;

/**
 *
 */
class GilbertLeadAPIController extends Controller
{
    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
