<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use App\Models\Office;
use GuzzleHttp\Client;

class GetAccessToken
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken(int $appId)
    {
        $url = env('EA_ACCESS_TOKEN_URL','https://identity-nonprod.energyaustralia.com.au/oauth2/ausca6h7qffFBySMx3l6/v1/token?grant_type=client_credentials');
        $options = [
          'headers'=>[
              'Authorization'=> $this->getBasicToken($appId)
          ],
            'form_params' => [
                'scope' => 'integration'
            ]
        ];
        $response = $this->client->post($url,$options);
        $responseData = json_decode($response->getBody()->getContents());
        return $responseData->token_type.' '.$responseData->access_token;
    }

    /**
     * dynamically get basic token
     *
     * @param int $leadId
     * @return string
     */
    private function getBasicToken(int $leadId): string
    {
        /** @var Office $office */
        $office =  ConnectionApplication::find($leadId)->office;
        return 'Basic ' . base64_encode($office->getEAClientId() . ':' . $office->getEAClientSecret());
    }
}
