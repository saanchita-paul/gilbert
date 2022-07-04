<?php

namespace App\Services\ChatBot;

use Illuminate\Support\Facades\Http;

class SendApplicationToChatbotAPI
{

    /**
     * Run POST Http Client
     *
     * @param object $application
     *
     * @return object
     *
     */
    public function postApi(object $application)
    {
        $url = "http://192.168.1.13:8888/api/application-data";

        try {
            $headers = [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ];

            $response = Http::withOptions([
                'headers' => $headers
            ])
                ->withBody(json_encode($application), "application/json")
                ->post($url);

            $response->throw();

            \Log::debug('Response data from api', [$response]);

            $responseData = json_decode($response->body(), true);

            \Log::debug('Response from API BODY', [$responseData]);
            \Log::debug('Response from API STATUS', [$response->status()]);
            \Log::debug('Response from API OK', [$response->ok()]);
            \Log::debug('Response from API SUCCESSFUL', [$response->successful()]);

            return $responseData;

        } catch (\Exception $exception) {
            \Log::error('Exception Message', [$exception->getMessage()]);
        }
    }
}
