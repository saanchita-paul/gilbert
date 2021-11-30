<?php

namespace PropertyMe\services;

use Illuminate\Support\Facades\Http;

class BasePropertyMeAPI
{

    /**
     * @var string|null $accessToken
     */
    private ?string $accessToken = null;

    /**
     * @throws \Exception
     */
    protected function refreshToken()
    {
        $url = config('property_me.refresh_token_url');
        $data = ["grant_type" => "refresh_token", "refresh_token" => config('property_me.refresh_token')];

        try {
            $response = Http::asForm()->withHeaders([
                "Authorization" => $this->getBasicAuth(),
            ])->post($url, $data);
            $this->accessToken = data_get(json_decode($response->body(), true), 'access_token');
        } catch (\Exception $exception) {
            throw new \Exception("[BasePropertyMeAPI:refreshToken] " . $exception->getMessage());
        }
    }

    private function getBasicAuth(): string
    {
        return "Basic " . base64_encode(config('property_me.client_id') . ":" . config('property_me.client_secret'));
    }

    /**
     * @throws \Exception
     */
    protected function getAccessToken(): string
    {
        if (!$this->accessToken) {
            $this->refreshToken();
        }
        return "Bearer " .$this->accessToken;
    }

}
