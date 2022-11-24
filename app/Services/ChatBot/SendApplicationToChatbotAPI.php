<?php

namespace App\Services\ChatBot;

use Illuminate\Support\Facades\Http;

class SendApplicationToChatbotAPI
{
    /**
     * Run POST Http Client
     *
     * @param $applicationDetails
     * @return mixed|void
     */
    public function postApi($applicationDetails)
    {
        $url = config('gb_to_cb.root_url') . config('gb_to_cb.endpoints.gb_to_cb_sync') . $applicationDetails['connection_application_id'];

        try {
            $headers = [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ];

            $response = Http::withOptions([
                'headers' => $headers
            ])
                ->withBody(json_encode($applicationDetails), "application/json")
                ->post($url);

            $response->throw();

            return json_decode($response->body(), true);
        } catch (\Exception $exception) {
            \Log::error('Exception Message', [$exception->getMessage()]);
        }
    }
}
