<?php

namespace App\Services\Utility;

use Illuminate\Support\Facades\Http;

class FastConnectService
{
    private string $_url_base;
    private string $_auth_url;
    private string $_authorization_header;
    private string $_address_url;

    public function __construct() {
        $this->_url_base = env('FC_URL_BASE', '');
        $this->_address_url = $this->_url_base . "/api/datafind/address";
        $this->_auth_url = $this->_url_base . "/oauth/token?grant_type=client_credentials&scope=datafind";
        $this->_authorization_header = "Basic " . env('FC_AUTH_TOKEN', '');
    }

    public function authenticate()
    {
        try {
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "authorization" => $this->_authorization_header,
            ])
                ->post($this->_auth_url);

            return json_decode($response->body(), true);
        }
        catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findAddress($body, $token)
    {
        try {
            $authorization_header = "Bearer " . $token;
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization_header,
            ])
                ->withBody($body,"application/json")
                ->post($this->_address_url);

            return json_decode($response->body(),true);
        }
        catch (\Exception $exception)
        {
            throw $exception;
        }
    }
}
