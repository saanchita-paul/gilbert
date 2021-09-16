<?php

namespace App\Services;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastConnectService
{
    private string $accessToken;

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])
            ->post( \config('fastconnect.root_url') . \config('fastconnect.get_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }

    public function searchAddress($body = [], $applicaionFlag = false, $id = null)
    {

       try{
           if($applicaionFlag)
           {
               $body = ConnectionApplication::find($id)->toArray();
           }

           $payload = FastConnectService::makeAddressPayload($body);

           $authorization = 'Bearer ' . $this->accessToken;
           $response = Http::withHeaders([
               'content-type' => 'application/json',
               'accept' => 'application/json',
               'authorization' => $authorization,
           ])
               ->withBody(json_encode($payload), 'application/json')
               ->post(\config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri'));

           $response_decoded = json_decode($response->body(), true);
           $mirn = NULL;
           $nmi = NULL;
           if (!empty($response_decoded['mirn']['result'])) {
               $mirn = $response_decoded['mirn']['result'][0]['mirn'];
           }

           if (!empty($response_decoded['nmi']['result'])) {
               $nmi = $response_decoded['nmi']['result'][0]['nmi'];
           }

           $error = $response_decoded['mirn']['error'] ?? $response_decoded['nmi']['error'];
           $no_result = empty($nmi) && empty($mirn);

           if ($no_result && !empty($error)) {
               throw new \ErrorException($error);
           }

           if($applicaionFlag) {
               $connectionApp = ConnectionApplication::find($id);
               $connectionApp->nmi = $nmi;
               $connectionApp->mirn = $mirn;
               $connectionApp->save();
           }

           return [
               'mirn' => $mirn,
               'nmi' => $nmi,
           ];
       } catch (\Exception $exception)
       {

           return [
               'mirn' => null,
               'nmi' => null,
           ];
       }
    }

    public static function makeAddressPayload($address = [])
    {

        return [
            'search_lookup_types' => [
                [
                    'lookup_provider_id' => 25,
                    'lookup_type' => 'nmi',
                ],
                [
                    'lookup_type' => 'mirn',
                ],
            ],

            'address' => [
                'street_name' => $address['street_name'] ?? '',
                'street_type' => $address['street_type'] ?? '',
                'suburb' => $address['city'] ?? '',
                'post_code' => $address['postcode'] ?? '',
                'state' => $address['state'] ? self::stateMap($address['state']): '',
                'street_number' => $address['street_number'] ?? '',
                'unit_number' => $address['unit_number'] ?? '',
            ],
        ];
    }

    public static function stateMap($state)
    {
        $stateList = ['New South Wales'=>'NSW','Victoria'=>'VIC','Queensland'=>'QLD',
            'South Australia'=>'SA','Northern Territory'=>'NT','TAS'=>'Tasmania','ACT'=>'Australian Capital Territory'];
        if(array_key_exists($state, $stateList))
        {
            return $stateList[$state];
        }
        return $state;

    }

}
