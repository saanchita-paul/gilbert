<?php

namespace App\Services\Utility;

use Illuminate\Support\Facades\Http;

class IgniteConnectionLeadService
{
    private $base_url;
    private $auth_url;
    private $connection_lead_url;
    private $authorization_header;
   
    public function __construct() {
        $this->base_url = env('IGNITE_BASE_URL');
        $this->auth_url = $this->base_url . "/oauth/token?grant_type=client_credentials";
        $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads";
        
        $client_id = env('IGNITE_CLIENT_ID');
        $client_secret = env('IGNITE_CLIENT_SECRET');
        $code = $client_id . ':' . $client_secret;

        $this->authorization_header = "Basic " . base64_encode($code);
    }

    public function authenticate()
    {
        try {
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "authorization" => $this->authorization_header,
            ])
            ->post($this->auth_url);
            
            $result = json_decode($response->body(), true);
            info($result['access_token']);
            $this->getIgniteLeads($result['access_token']);
            // return json_decode($response->body(), true);
        }
        catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getIgniteLeads($token)
    {
        // $url  = $this->connection_lead_url . '?happenedSince=2021-10-05T22%3A47%3A01.604Z';
        $url  = $this->connection_lead_url . '?happenedSince=2021-10-05T22%3A47%3A01.604Z';
        info($url);
        try {
            $authorization_header = "Bearer " . $token;
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization_header,
            ])
                ->get($this->connection_lead_url , [ 'happenedSince' => '2020-08-27T22:47:01.604Z' ]);

            info('authenticate');
            info($response->body());
            return json_decode($response->body(),true);
        }
        catch (\Exception $exception)
        {
            throw $exception;
        }
    }
}
