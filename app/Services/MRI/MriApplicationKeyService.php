<?php

namespace App\Services\MRI;

use Illuminate\Support\Facades\Http;

class MriApplicationKeyService
{
    public function getData()
    {
        $url = config('mri.url') . config('mri.app_key');

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Ocp-Apim-Subscription-Key" => config('mri.subscription_key')
            ])->get($url);

            return json_decode($response->body(), true);
        } catch (\Exception $exception) {
            \Log::error('Error: ', [$exception->getMessage(), $exception->getTraceAsString()]);
        }
    }
}
