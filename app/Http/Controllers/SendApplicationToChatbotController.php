<?php

namespace App\Http\Controllers;

use App\Services\SendAppGilbertToChatbotService;
use Illuminate\Http\Request;

class SendApplicationToChatbotController extends Controller
{
    public function sendApplication(Request $request, int $applicationId): object
    {
        try {
            $service = new SendAppGilbertToChatbotService();
            return $service->sendApplication($applicationId);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
