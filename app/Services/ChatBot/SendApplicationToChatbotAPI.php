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
     * @throws exception
     */
    public function postApi(object $application)
    {
        $url = config('gb_to_cb.root_url') . config('gb_to_cb.endpoints.gb_to_cb_sync') . $application->id;

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
