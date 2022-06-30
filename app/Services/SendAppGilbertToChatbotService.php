<?php

namespace App\Services;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;

class SendAppGilbertToChatbotService
{
    public function sendApplication(int $applicationId): object
    {
        $application = ConnectionApplication::query()
            ->with(['connectionServices'])
            ->where('id', $applicationId)->first();

        $url = "http://192.168.1.13:8888/api/application-data";

        return $responseData = $this->postApi($url, $application);
    }

    protected function postApi(string $url, object $body)
    {
        try {
            $headers = [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ];

            $response = Http::withOptions([
                'headers' => $headers
            ])
                ->withBody(json_encode($body), "application/json")
                ->post($url);
            \Log::info('data', [$response]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }
    }
}
