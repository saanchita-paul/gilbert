<?php

namespace PropertyMe\services;

use Illuminate\Support\Facades\Http;

class BasePropertyMeAPI
{

    private string $accessToken;

    /**
     * @throws \Exception
     */
    public function refreshToken()
    {
        $url = config('property_me.refresh_token_url');
        $data = ["grant_type" => "refresh_token", "refresh_token" => config('property_me.refresh_token')];

        try {
            $response = Http::asForm()->withHeaders([
                "Authorization" => $this->getBasicAuth(),
            ])->post($url, $data);
            $this->accessToken = data_get(json_decode($response->body(), true), 'access_token');
            return $this->accessToken;
        } catch (\Exception $exception) {
            throw new \Exception("[BasePropertyMeAPI:refreshToken] " . $exception->getMessage());
        }
    }

    private function getBasicAuth(): string
    {
        return "Basic " . base64_encode(config('property_me.client_id') . ":" . config('property_me.client_secret'));
    }

    public function getContacts(String $token) : array
    {
        $noOfDays = config('property_me.no_of_days') ?? 1;
        $earlierDate = date('Y-m-d',strtotime("-{$noOfDays} days"));
        $dateTime = new \DateTime($earlierDate);
        $unixDate = $dateTime->format('U');

        $base_url = config('property_me.api_root_url');
        $get_contact_url = config('property_me.get_contact_url');
        $lead_url = $base_url . $get_contact_url . "?Timestamp={$unixDate}";

        try {
            $authorization_header = "Bearer " . $token;
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization_header,
            ])
            ->get($lead_url);

            $result = json_decode($response->body(), true);
            \Log::info($result);
            dd($result);
            return $result;
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }
}
