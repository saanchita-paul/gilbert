<?php

namespace App\Services\ChatBot;

use App\Models\ConnectionApplication;

class SendAppGilbertToChatbotService
{
    public function __construct(private int $applicationId)
    {
    }

    /**
     * connection application
     */
    public function sendApplication()
    {
        $application = ConnectionApplication::findOrFail($this->applicationId);
        $application->load(['connectionServices.reasons', 'identification', 'authorizedPerson', 'office', 'agency']);
        $application->update(['is_locked' => true]);
        $sendApplication = new SendApplicationToChatbotAPI();
        return $sendApplication->postApi($application);
    }

}
