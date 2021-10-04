<?php


namespace App\Services\Sales;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class GetAccessToken
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        $url = env('EA_ACCESS_TOKEN_URL','https://identity-nonprod.energyaustralia.com.au/oauth2/ausca6h7qffFBySMx3l6/v1/token?grant_type=client_credentials');
        $options = [
          'headers'=>[
              'Authorization'=>env('BASE_64_CREDENTIAL', 'Basic MG9hb3hsaGxoRVRtNUprVUwzbDY6R0ZHckxTXzZNVmxHdFQ2MzM0Y05xMUFoV2pVTkxjanNnWmgwa055YQ==')
          ],
            'form_params' => [
                'scope' => 'integration'
            ]
        ];
        $response = $this->client->post($url,$options);
        $responseData = json_decode($response->getBody()->getContents());
        return $responseData->token_type.' '.$responseData->access_token;

    }
}
