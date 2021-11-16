<?php

namespace Ignite\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class IgniteConnectionLeadService
{
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $base_url;
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $auth_url;
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $connection_lead_url;
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $authorization_header;
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $nextPageUrl;
    /**
     * Base Url of the ignite api.
     *
     * @var string
     */
    private String $token;



    /**
     * set nextPageUrl.
     *
     * @param  String $nextPageUrl
     * @return void
     */
    private function setNextPageUrl(?String $nextPageUrl = '') : void{
        $this->nextPageUrl = $nextPageUrl != '' ? 
                             $this->base_url . "/applications/v1/rental" . $nextPageUrl : '';
    }

    /**
     * get nextPageUrl.
     *
     * @return String $nextPageUrl
     */
    public function getNextPageUrl() : String{
        return $this->nextPageUrl;
    }

    /**
     * set api token.
     *
     * @return void
     */
    private function setToken($token) : void{
        $this->token = $token;
    }

    /**
     * get api token.
     *
     * @return String $token
     */
    public function getToken() : String{
        return $this->token;
    }

    /**
     * it will set connection lead url
     *
     * @return void
     */
    private function setConnctionLeadUrl() : void{
        
        $noOfDays = config('ignite.NO_OF_DAY_IGNITE_LEAD') ?? 1;

        $isoDateYesterday = date('Y-m-d',strtotime("-{$noOfDays} days"));
        $this->base_url = config('ignite.IGNITE_BASE_URL');
        $this->auth_url = $this->base_url . "/oauth/token?grant_type=client_credentials";
        $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads" . "?happenedSince={$isoDateYesterday}T00%3A00%3A01.604Z" ;
    }

    /**
     * get connection_lead_url.
     *
     * @return String $connection_lead_url
     */
    public function getConnctionLeadUrl() : String{
        return $this->connection_lead_url;
    }

    public function __construct() {
        $this->setConnctionLeadUrl();
        
        $this->nextPageUrl = '';

        $client_id = config('ignite.IGNITE_CLIENT_ID');
        $client_secret = config('ignite.IGNITE_CLIENT_SECRET');
        $code = $client_id . ':' . $client_secret;

        $this->authorization_header = "Basic " . base64_encode($code);
    }

    /**
     * authenticate.
     *
     * @return String $token
     */
    public function authenticate() : String
    {
        try {
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "authorization" => $this->authorization_header ,
            ])
            ->post($this->auth_url);
            
            $result = json_decode($response->body(), true);
            \Log::info('in the authenticate token');
            \Log::info($result);

            if(isset($result['errors'])){
                throw new Exception("access token is invalid", 1);
            }else{
                $this->setToken($result['access_token']);
                return $this->token;
            }
        }
        catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * send array of leads.
     *
     * @param  String $token
     * @param  String|null $url
     * @return array
     */
    public function getIgniteLeads(String $token ,?String $url = null) : array
    {
        try {
            $authorization_header = "Bearer " . $token;
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization_header,
            ])
            ->get($url);

            $result = json_decode($response->body(), true);
            info('Result fetching from ignite');
            \Log::info($result);
            $this->setNextPageUrl( $result['_links']['next']['href'] ?? '' );
            return isset($result['errors']) ? throw new Exception("access token is invalid", 1) : $result['_embedded']['connectionLeads'] ;
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }
}
