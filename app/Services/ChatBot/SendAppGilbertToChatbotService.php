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
        $application->load(['connectionServices', 'identification', 'authorizedPerson']);

        $sendApplication = new SendApplicationToChatbotAPI();
        $response = $sendApplication->postApi($application);

//        if ($response){
//            $application->update([
//                'is_sent_to_chatbot' => 1
//            ]);
//        }

        return $response;
    }

}
