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
        $response = Http::retry(2)->withHeaders([
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
            $response = Http::retry(2)->timeout(150)->withHeaders([

                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => $authorization,
            ])
                ->withBody(json_encode($payload), 'application/json')
                ->post(config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri')); //CHANGE
            // ->post("https://api.fastconnect.net.au/api/datafind/address");

            $responseDecoded = json_decode($response->body(), true);

            $parsedValues = $this->parseValues($responseDecoded);

            $mirn = $parsedValues['mirn'];
            $nmi = $parsedValues['nmi'];

            if ($applicationFlag) {
                $connectionApp = ConnectionApplication::find($id);
                $connectionApp->nmi = $nmi;
                $connectionApp->nmi_score = $parsedValues['nmi_score'];
                $connectionApp->mirn = $mirn;
                $connectionApp->mirn_score = $parsedValues['mirn_score'];
                $connectionApp->save();
            }

            return $parsedValues;
        } catch (\Exception $exception) {
            Log::warning("FastConnectService: " . $exception->getMessage());
            Log::warning("FastConnectService: " . $exception->getTraceAsString());
            return [
                'mirn' => null,
                'mirn_score' => 'SERVER_ERROR',
                'nmi' => null,
                'nmi_score' => 'SERVER_ERROR',
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

    /**
     * @param array $response
     *
     * @return array{mirn: ?numeric, mirn_score: string, nmi: ?numeric, nmi_score: string}
     */
    private function parseValues(array $response): array
    {
        $mirns = $this->parseMirn($response['mirn']['result'] ?? null);
        $nmis = $this->parseNmi($response['nmi']['result'] ?? null);

        return array_merge($mirns, $nmis);
    }

    private function parseNmi(?array $nmis): array
    {
        $res = [
            'nmi' => null,
            'nmi_score' => 'NO_RESULT',
        ];
        if (!$nmis) {
            return $res;
        }

        $exact = [];

        foreach ($nmis as $nmi) {
            if (isset($nmi['nmi']) && ($nmi['match_type'] ?? null) === "EXACT" && ($nmi['status'] ?? null) === 'ACTIVE') {
                $exact[] = $nmi['nmi'];
            }
        }

        if (sizeof($exact) === 0 ) {
            return $res;
        }

        if (sizeof($exact) > 1) {
            $res['nmi_score'] = 'MULTIPLE_EXACT';
            return  $res;
        }

        $res['nmi_score'] = 'EXACT';
        $res['nmi'] = $exact[0];

        return  $res;
    }

    private function parseMirn(?array $mirns): array
    {
        $res = [
            'mirn' => null,
            'mirn_score' => 'NO_RESULT',
        ];
        if (!$mirns) {
            return $res;
        }

        $exact = [];

        foreach ($mirns as $mirn) {
            if (isset($mirn['mirn']) && ($mirn['match_type'] ?? null) === "EXACT") {
                $exact[] = $mirn['mirn'];
            }
        }

        if (sizeof($exact) === 0 ) {
            return $res;
        }

        if (sizeof($exact) > 1) {
            $res['mirn_score'] = 'MULTIPLE_EXACT';
            return  $res;
        }

        $res['nmi_score'] = 'EXACT';
        $res['mirn'] = $exact[0];

        return  $res;
    }
}
