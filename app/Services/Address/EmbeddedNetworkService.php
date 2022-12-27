<?php

namespace App\Services\Address;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbeddedNetworkService
{
    private string $accessToken;

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])->post(\config('fastconnect.root_url') . \config('fastconnect.get_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
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

    public static function makeAddressPayloadWithoutUnit($address = [])
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
                'street_number' => $address['street_number'] ?? ''
            ],
        ];
    }

    public function searchAddressWithoutUnit($body = [], $applicationFlag = false, $id = null)
    {
        try {
            if ($applicationFlag) {
                $body = ConnectionApplication::find($id)->toArray();
            }

            $mirn = null;
            $nmi = null;

            if (config('fastconnect.embedded_enabled')) {
                $payload = self::makeAddressPayloadWithoutUnit($body);

                Log::info('Fetch MIRN/NMI payload: ', $payload);

                $authorization = 'Bearer ' . $this->accessToken;
                $response = Http::withHeaders([
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => $authorization,
                ])->withBody(json_encode($payload), 'application/json')
                    ->post(\config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri'));

                $response_decoded = $response->json();

                Log::info('Fetch MIRN/NMI response: ', $response_decoded);


                if (!empty($response_decoded['mirn']['result']) && count($response_decoded['mirn']['result']) > 0) {
                    $mirn = $response_decoded['mirn']['result'][0]['mirn'];
                }

                if (!empty($response_decoded['nmi']['result']) && count($response_decoded['nmi']['result']) > 0) {
                    Log::info('Get ' . count($response_decoded['nmi']['result']) . ' NMI result.');
                    $nmi = $response_decoded['nmi']['result'][0]['nmi'];
                }

                $error = $response_decoded['mirn']['error'] ?? $response_decoded['nmi']['error'];
                $no_result = empty($nmi) && empty($mirn);

                if ($no_result && !empty($error)) {
                    throw new \ErrorException($error);
                }
            }

            /*if ($applicationFlag) {
                $connectionApp = ConnectionApplication::find($id);
                $connectionApp->nmi = $nmi;
                $connectionApp->mirn = $mirn;
                $connectionApp->save();
            }*/

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

    private static function makeNmiPayload($nmi)
    {
        return [
            'nmi' => [
                'nmi' => $nmi,
                'lookup_provider_id' => 25,
            ]
        ];
    }

    public function fetchEmbeddedNetwork($nmi = null, $applicationFlag = false, $applicationId = null)
    {
        try {
            $is_embedded = null;

            if (config('fastconnect.embedded_enabled') && $nmi) {
                $payload = self::makeNmiPayload($nmi);
                Log::info('Embedded Network Payload: ', $payload);

                $authorization = 'Bearer ' . $this->accessToken;
                $response = Http::withHeaders([
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => $authorization,
                ])
                    ->withBody(json_encode($payload), 'application/json')
                    ->post(config('fastconnect.root_url') . config('fastconnect.embedded_nmi_uri'));

                $responseData = $response->json();

                Log::info('Embedded Network Response: ', $responseData);


                if (!empty($responseData['nmi']['result'])) {
                    $is_embedded = $responseData['nmi']['result']['master_data']['embedded_network'];
                }

                $error = $responseData['nmi']['error'];
                $no_result = empty($is_embedded);

                if ($no_result && !empty($error)) {
                    throw new \ErrorException($error);
                }
            }

            if ($applicationFlag) {
                $connectionApp = ConnectionApplication::find($applicationId);
                $connectionApp->update(['embedded_nmi' => $is_embedded]);
            }

            return [
                'embedded_nmi' => $is_embedded,
            ];
        } catch (\Exception $exception) {

            Log::info('Embedded Network Error: ', $exception->getMessage());

            return [
                'embedded_nmi' => null
            ];
        }
    }
}
