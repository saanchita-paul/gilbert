<?php

namespace App\Services\Utility;

use Illuminate\Support\Facades\Http;

class FastConnectService
{
    private $base_url;
    private $auth_url;
    private $connection_lead_url;
    private $authorization_header;
   
    public function __construct() {
        $this->base_url = env('IGNITE_BASE_URL');
        $this->auth_url = $this->base_url . "/oauth/token?grant_type=client_credentials";
        $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads";

        // $this->authorization_header = "Basic " . base64_encode(env('IGNITE_CLIENT_ID'):env('IGNITE_CLIENT_ID'));
    }

    public function authenticate()
    {
        try {
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "authorization" => $this->authorization_header,
            ])
                ->post($this->auth_url);

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
                ->post($this->connection_lead_url);

            return json_decode($response->body(),true);
        }
        catch (\Exception $exception)
        {
            throw $exception;
        }
    }
}
