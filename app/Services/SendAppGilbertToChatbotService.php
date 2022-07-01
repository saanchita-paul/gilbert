<?php

namespace App\Services;

use App\Models\ConnectionApplication;

class SendAppGilbertToChatbotService
{
    public function sendApplication(int $applicationId)
    {
        $application = ConnectionApplication::query()
            ->with(['connectionServices', 'identification', 'authorizedPerson'])
            ->where('id', $applicationId)->first();

        $callAPI = new SendApplicationToChatbotAPI();
        $responseData = $callAPI->postApi($application);

        \Log::debug('Response from service', [$responseData]);

        return $responseData;
    }

}
