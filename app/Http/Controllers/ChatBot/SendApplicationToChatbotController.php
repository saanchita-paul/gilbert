<?php

namespace App\Http\Controllers\ChatBot;

use App\Http\Controllers\Controller;
use App\Services\ChatBot\SendAppGilbertToChatbotService;
use Illuminate\Http\Request;

class SendApplicationToChatbotController extends Controller
{
    public function sendApplication(Request $request, int $applicationId)
    {
        try {
            $service = new SendAppGilbertToChatbotService($applicationId);
            return $service->sendApplication();
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
