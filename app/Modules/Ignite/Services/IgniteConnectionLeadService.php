<?php

namespace Ignite\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class IgniteConnectionLeadService
{
    private $base_url;
    private $auth_url;
    private $connection_lead_url;
    private $authorization_header;
    private $nextPageUrl;

    private $mockData = '{
        "_embedded": {
            "connectionLeads": [{
                "application": {
                    "id": "7754cd3f-1008-40e3-bc94-cdaca634a119",
                    "approvedAt": "2021-10-14T06:48:41.011085Z"
                },
                "tenant": {
                    "firstName": "Subi",
                    "lastName": "Deb",
                    "email": "subiwork465@gmail.com",
                    "mobilePhoneNumber": "1234567890",
                    "birthDate": "2005-02-02"
                },
                "property": {
                    "street": "24 Penn street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-10-15"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }, {
                "application": {
                    "id": "2aa6b187-7780-410c-b10a-a4033731a183",
                    "approvedAt": "2021-10-14T07:10:27.433599Z"
                },
                "tenant": {
                    "firstName": "Subi",
                    "lastName": "Deb",
                    "email": "subiwork465@gmail.com",
                    "mobilePhoneNumber": "1234567890",
                    "birthDate": "2000-02-02"
                },
                "property": {
                    "street": "100 TimberTest Street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-10-22"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [{
                    "id": "2362810",
                    "name": "Subi Deb",
                    "email": "subi.deb@rea-group.com"
                }],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }, {
                "application": {
                    "id": "ac3da80b-34fe-4ca7-acd6-9b27502d176d",
                    "approvedAt": "2021-10-28T08:46:32.154688Z"
                },
                "tenant": {
                    "firstName": "Yan Update",
                    "lastName": "Li",
                    "email": "marcus.li@qq.com",
                    "mobilePhoneNumber": "0416222333",
                    "birthDate": "2004-01-01"
                },
                "property": {
                    "street": "10 Victory Street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-10-30"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [{
                    "id": "2395822",
                    "name": "Paul &#x26; Gina O",
                    "email": "luke.buckley@realestate.com.au"
                }],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }, {
                "application": {
                    "id": "2240b317-7ab7-4ec4-afa5-2be4856a3521",
                    "approvedAt": "2021-11-03T12:49:33.800612Z"
                },
                "tenant": {
                    "firstName": "Rikki",
                    "lastName": "Markota",
                    "email": "rikki.markota@realestate.com.au",
                    "mobilePhoneNumber": "0428347172",
                    "birthDate": "1996-01-02"
                },
                "property": {
                    "street": "91 James Street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-10-30"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [{
                    "id": "2362810",
                    "name": "Subi Deb",
                    "email": "subi.deb@rea-group.com"
                }],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }, {
                "application": {
                    "id": "39ce7678-bf41-4854-8875-2f5764de9170",
                    "approvedAt": "2021-11-04T02:12:59.457309Z"
                },
                "tenant": {
                    "firstName": "Subi",
                    "lastName": "Deb",
                    "email": "subiwork465@gmail.com",
                    "mobilePhoneNumber": "1234567890",
                    "birthDate": "2000-02-02"
                },
                "property": {
                    "street": "22 Pen Test Street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-11-05"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [{
                    "id": "2362810",
                    "name": "Subi Deb",
                    "email": "subi.deb@rea-group.com"
                }],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }, {
                "application": {
                    "id": "00c428b4-6677-472b-864b-3ec06741d031",
                    "approvedAt": "2021-11-04T02:17:09.971049Z"
                },
                "tenant": {
                    "firstName": "Subi",
                    "lastName": "Deb",
                    "email": "subiwork465@gmail.com",
                    "mobilePhoneNumber": "1234567890",
                    "birthDate": "2000-02-02"
                },
                "property": {
                    "street": "23 Penn Street",
                    "suburb": "Wycliffe Well",
                    "state": "NT",
                    "postcode": "0862",
                    "moveInDate": "2021-11-30"
                },
                "agency": {
                    "reaId": "VKSYFQ",
                    "name": "Pippin & Hall Real Estate - Wycliffe Well"
                },
                "agents": [{
                    "id": "2362810",
                    "name": "Subi Deb",
                    "email": "subi.deb@rea-group.com"
                }],
                "connectionProviderName": "Hood",
                "utilityConnectionsAllowed": ["all"]
            }]
        },
        "_links": {
            "self": {
                "href": "/connection-leads?happenedSince=2021-10-05T22%3A47%3A01.604Z"
            },
            "next": {
                "href": "/connection-leads?happenedSince=2021-11-04T02%3A17%3A09.971049Z"
            }
        }
    }
    ';

    private function setNextPageUrl(String $nextPageUrl){
        $this->nextPageUrl = $nextPageUrl;
    }

    public function getNextPageUrl(){
        return $this->nextPageUrl;
    }

    private function setConnctionLeadUrl(){
        \Log::info(date('c',strtotime("-1 days")) );
        $isoDateYesterday = date('Y-m-d',strtotime("-1 days"));
        $this->base_url = env('IGNITE_BASE_URL');
        $this->auth_url = $this->base_url . "/oauth/token?grant_type=client_credentials";
        $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads" . "?happenedSince={$isoDateYesterday}T22%3A47%3A01.604Z" ;
        // $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads" . "?happenedSince=2021-10-05T22%3A47%3A01.604Z" ;
        \Log::info($this->connection_lead_url);
    }

    public function getConnctionLeadUrl(){
        return $this->connection_lead_url;
    }

    public function __construct() {
        // $this->base_url = env('IGNITE_BASE_URL');
        // $this->auth_url = $this->base_url . "/oauth/token?grant_type=client_credentials";
        // $this->connection_lead_url = $this->base_url . "/applications/v1/rental/connection-leads";
        
        $this->setConnctionLeadUrl();

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
                "authorization" => $this->authorization_header ,
            ])
            ->post($this->auth_url);
            
            $result = json_decode($response->body(), true);
            \Log::info('in the authenticate');
            \Log::info($result);
            // \Log::info($result['access_token']);
            // if( isset($result['errors']) ){
            //     \Log::error('an error occured');
            //     throw new Exception("access token is invalid", 1);
            // }else{
            //     $this->getIgniteLeads($result['access_token']);
            // }

            // isset($result['errors']) ? throw new Exception("access token is invalid", 1) : $this->getIgniteLeads($result['access_token']);
            return isset($result['errors']) ? throw new Exception("access token is invalid", 1) : $result['access_token'];
            // \Log::info($result['errors'][0]['status']);
            // $u = $result['errors'][0]['status'];
            // return $result['access_token'];
            // $this->getIgniteLeads($result['access_token']);
            // return json_decode($response->body(), true);
        }
        catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            // \Log::error($exception->getTrace());
            throw $exception;
        }
    }

    public function getIgniteLeads($token , $url = null)
    {
        // $url  = $this->connection_lead_url . '?happenedSince=2021-10-05T22%3A47%3A01.604Z';
        // $url  = $this->connection_lead_url . '?happenedSince=2021-10-05T22%3A47%3A01.604Z';
        info($url);
        try {
            info('in the getLeads');
            \Log::info($this->connection_lead_url);
            info('in the getLeads1');
            $authorization_header = "Bearer " . $token;
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization_header,
            ])
            ->get($this->connection_lead_url);

            $result = json_decode($response->body(), true);
            // $result = json_decode( $this->mockData , true );
            info('in the get ignite lead');
            \Log::info($result);

            // if( isset($result['errors']) ){
            //     \Log::error('an error occured');
            //     throw new Exception("access token is invalid", 1);
            // }else{}
            \Log::info($result['_links']);
            \Log::info($result['_links']['next']['href'] ?? 'no next');
            // isset($result['errors']) ? throw new Exception("access token is invalid", 1) : '' ;
            $this->setNextPageUrl( $result['_links']['next']['href'] ?? null );

            return isset($result['errors']) ? throw new Exception("access token is invalid", 1) : $result['_embedded']['connectionLeads'] ;
            // return isset($result['errors']) ? throw new Exception("access token is invalid", 1) : $this->mockData ;
            // info('authenticate');
            // info($response->body());
            // info($result['_embedded']['connectionLeads']);
            // return json_decode($response->body(),true);
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
            // \Log::error($exception->getTrace());
            throw $exception;
        }
    }
}
