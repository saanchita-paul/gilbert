<?php

namespace App\Http\Controllers\ChatBot;

use App\Http\Controllers\Controller;
use App\Jobs\GilbertToChatbotSyncJob;
use App\Services\GilbertToCB\GilbertToChatbotSyncService;
use Illuminate\Http\JsonResponse;

class SendApplicationToChatbotController extends Controller
{
    /**
     * Send application to chatBot
     *
     * @param int $applicationId
     * @return JsonResponse
     */
    public function sendApplication(int $applicationId): JsonResponse
    {
        try {
            $service = new GilbertToChatbotSyncService($applicationId);
            $service->lockApp();
            GilbertToChatbotSyncJob::dispatch($applicationId);
            return $this->sendSuccessResponse('success');
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
