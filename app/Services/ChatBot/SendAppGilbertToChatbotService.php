<?php

namespace App\Services\ChatBot;

use App\Models\ConnectionApplication;

class SendAppGilbertToChatbotService
{
    public function __construct(private int $applicationId)
    {
    }

    public function sendApplication()
    {
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $application->load(['connectionServices', 'identification', 'authorizedPerson']);

        $sendApplication = new SendApplicationToChatbotAPI();
        $responseData = $sendApplication->postApi($application);

        \Log::debug('Response from service', [$responseData]);

        return $responseData;
    }

}
