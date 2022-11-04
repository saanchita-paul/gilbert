<?php

namespace App\Services\MRI;

use Illuminate\Support\Facades\Http;

class MriApplicationKeyService
{
    public function getData()
    {
        $url2 = config('mri.url') . config('mri.app_key');
        \Log::info('URL :', [$url2]);
        $subs_key2 = config('mri.subscription_key');

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Ocp-Apim-Subscription-Key" => $subs_key2
            ])->get($url2);

            $result = json_decode($response->body(), true);
            \Log::info('MRI data fetch', [$result]);
            return $result[0];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    }
}
