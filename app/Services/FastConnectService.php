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
            // 'authorization' => "Basic bGl2ZV8ycUMxMU1ZTW9ZZURqeGlLMVJRc0hrU2o6SWRYNHZnUXoyMDZ0OWNjcFJqMlRvbTN4UU1MS0IzUXRWTDJxQ3d2NnE3SGx4OHQ1",
        ])
            ->post(\config('fastconnect.root_url') . \config('fastconnect.get_token_uri'));
        // ->post("https://api.fastconnect.net.au/oauth/token?grant_type=client_credentials&scope=datafind");

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }

    public function searchAddress($body = [], $applicationFlag = false, $id = null)
    {
        try {
            if ($applicationFlag) {
                $body = ConnectionApplication::find($id)->toArray();
            }

            $payload = FastConnectService::makeAddressPayload($body);

            Log::info('fast connect payload', $payload);

            $authorization = 'Bearer ' . $this->accessToken;
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => $authorization,
            ])
                ->withBody(json_encode($payload), 'application/json')
                ->post(\config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri')); //CHANGE
            // ->post("https://api.fastconnect.net.au/api/datafind/address");

            $response_decoded = json_decode($response->body(), true);
            $mirn = null;
            $nmi = null;
            if (!empty($response_decoded['mirn']['result']) && count($response_decoded['mirn']['result']) > 1) {
                $mirn = $response_decoded['mirn']['result'][0]['mirn'];
            }

            if (!empty($response_decoded['nmi']['result']) && count($response_decoded['nmi']['result']) > 1) {
                $nmi = $response_decoded['nmi']['result'][0]['nmi'];
            }

            $error = $response_decoded['mirn']['error'] ?? $response_decoded['nmi']['error'];
            $no_result = empty($nmi) && empty($mirn);

            if ($no_result && !empty($error)) {
                throw new \ErrorException($error);
            }

            if ($applicationFlag) {
                $connectionApp = ConnectionApplication::find($id);
                $connectionApp->nmi = $nmi;
                $connectionApp->mirn = $mirn;
                $connectionApp->save();
            }

            return [
                'mirn' => $mirn,
                'nmi' => $nmi,
            ];
        } catch (\Exception $exception) {

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
                'street_name' => $address['street_name_only'],
                'street_type' => $address['street_type'],
                'suburb' => $address['city'] ?? '',
                'post_code' => $address['postcode'] ?? '',
                'state' => $address['state'] ? self::stateMap($address['state']) : '',
                'street_number' => $address['street_number'] ?? '',
                'unit_number' => $address['unit_number'] ?? '',
            ],
        ];
    }

    public static function stateMap($state)
    {
        $stateList = [
            'New South Wales' => 'NSW',
            'Victoria' => 'VIC',
            'Queensland' => 'QLD',
            'South Australia' => 'SA',
            'Northern Territory' => 'NT',
            'TAS' => 'Tasmania',
            'ACT' => 'Australian Capital Territory',
            'WA' => 'Western Australia'];
        if (array_key_exists($state, $stateList)) {
            return $stateList[$state];
        }
        return $state;

    }

    public static function makeNmiPayload($nmi)
    {
        return [
            'nmi' => [
                'nmi' => $nmi,
                'lookup_provider_id' => 25,
            ]
        ];
    }

    public function fetchEmbeddedNetwork($nmi = "", $applicationFlag = false, $id = null)
    {
        try {
            if ($applicationFlag) {
                $nmi = ConnectionApplication::find($id, ['nmi'])->nmi;
            }

            $payload = FastConnectService::makeNmiPayload($nmi);

            Log::info('fast connect embedded payload', $payload);

            $authorization = 'Bearer ' . $this->accessToken;
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => $authorization,
            ])
                ->withBody(json_encode($payload), 'application/json')
                ->post(config('fastconnect.root_url') . config('fastconnect.embedded_nmi_uri'));

            $responseData = $response->json();

            $is_embedded = null;
            if (!empty($responseData['nmi']['result'])) {
                $is_embedded = $responseData['nmi']['result']['master_data']['embedded_network'];
            }

            $error = $responseData['nmi']['error'];
            $no_result = empty($is_embedded);

            if ($no_result && !empty($error)) {
                throw new \ErrorException($error);
            }

            if ($applicationFlag) {
                $connectionApp = ConnectionApplication::find($id);
                $connectionApp->update(['is_embedded' => $is_embedded]);
            }

            return [
                'is_embedded' => $is_embedded,
            ];
        } catch (\Exception $exception) {

            return [
                'is_embedded' => null
            ];
        }
    }
}
