<?php

namespace App\Services\ChatBot;

use Illuminate\Support\Facades\Http;

class SendApplicationToChatbotAPI
{
    const BASE_URL = 'http://192.168.1.13:8888/api';

    /**
     * Run POST Http Client
     *
     * @param object $application
     *
     * @return object
     *
     * @throws exception
     */
    public function postApi(object $application)
    {
        $url = self::BASE_URL. '/application-data';

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

            return json_decode($response->body(), true);

        } catch (\Exception $exception) {
            \Log::error('Exception Message', [$exception->getMessage()]);
        }

    }
}
