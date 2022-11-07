<?php

namespace App\Services\MRI;

use Illuminate\Support\Facades\Http;

class MriApplicationKeyService
{
    /**
     * Get MRI office key pairs
     */
    public function getData()
    {
        $url = $this->getURL();

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

    /**
     * Get MRI office key pairs URL
     *
     * @return string
     */
    private function getURL(): string
    {
        return config('mri.base_url') . config('mri.endpoints.get_mri_office_key_pairs') . config('mri.app_key');
    }
}
