<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class FastConnectService
{
    private string $accessToken;
    private array $fcData;


    public function authenticate()
    {

        $response = Http::withHeaders([
                "content-type" => "application/json",
                "Authorization" => \config('fastconnect.base64_key')]
        )
            ->post( \config('fastconnect.root_url') .  \config('fastconnect.get_token_uri'));


        return $this->accessToken = json_decode($response->body(), true)['access_token'];
        return $this;

    }

    public function searchAddress($body = [])
    {
        $authorization = "Bearer " . $this->accessToken;
        $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => $authorization]
        )->withBody($body, "application/json")
            ->post(\config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri'));


        $this->fcData = json_decode($response->body(), true);
        return $this;

    }

    public function toArray()
    {
        # TODO we have parse fc data
        return [
            'nmi' => '0987565555',
            'mirn' => '5678765555'
        ];
    }

    public function makePayload($address = [])
    {
            #Make Like sample data
        $data = '{
    "search_lookup_types": [
        {
            "lookup_provider_id": 1,
            "lookup_type": "nmi"
        },{
            "lookup_type": "mirn"
        }
    ],
    "address": {
        "street_name": "Main",
        "street_type": "Ave",
        "suburb": "Lidcombe",
        "post_code": "2141",
        "state": "NSW",
        "street_number": "1"
    }
}';
    }

}
