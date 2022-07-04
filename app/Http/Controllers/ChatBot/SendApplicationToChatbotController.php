<?php

namespace App\Http\Controllers\ChatBot;

use App\Http\Controllers\Controller;
use App\Services\ChatBot\SendAppGilbertToChatbotService;
use Illuminate\Http\JsonResponse;

class SendApplicationToChatbotController extends Controller
{
    public function sendApplication( int $applicationId)
    {
        try {
            $service = new SendAppGilbertToChatbotService($applicationId);
            return new JsonResponse($service->sendApplication());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
