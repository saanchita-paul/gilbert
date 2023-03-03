<?php

namespace App\Services\MRI;

use Illuminate\Support\Facades\Http;

class MriApplicationKeyService
{
    /**
     * @var string
     */

    /**
     * Get MRI office key pairs
     */
    public function getData()
    {
        $url = $this->getURL();
        $subKey = empty(config('mri.subscription_key')) ? '574800735b3b4effa9d8ef84d57d345f' : config('mri.subscription_key');

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Ocp-Apim-Subscription-Key" => $subKey
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
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.get_mri_office_key_pairs')) ? '/apikey/v1/application_keys/' : config('mri.endpoints.get_mri_office_key_pairs');
        $appKey = empty(config('mri.app_key')) ? '4e1df42e-5c53-4762-b07a-79f8d731e0bc' : config('mri.app_key');

        return $url . $endpoint . $appKey;
    }
}
