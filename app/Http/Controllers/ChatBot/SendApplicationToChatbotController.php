<?php

namespace App\Http\Controllers\ChatBot;

use App\Http\Controllers\Controller;
use App\Models\ConnectionApplication;
use App\Services\ChatBot\SendAppGilbertToChatbotService;
use App\Services\GilbertToCB\GilbertToChatbotSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendApplicationToChatbotController extends Controller
{
    public function sendApplication(int $applicationId)
    {
        try {
            $service = new GilbertToChatbotSyncService($applicationId);
            $service->sync();
            return $this->sendSuccessResponse('success');
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
