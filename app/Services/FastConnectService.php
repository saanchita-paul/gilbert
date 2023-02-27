<?php

namespace App\Services;

use App\Models\ConnectionApplication;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use voku\helper\ASCII;

class FastConnectService
{
    public const NO_RESULT = 'NO_RESULT';
    public const NO_EXACT_OR_ACTIVE = 'NO_EXACT_OR_ACTIVE';
    public const EXACT = 'EXACT';
    public const MULTIPLE_EXACT = 'MULTIPLE_EXACT';


    private string $accessToken;
    private array $payload;

    public function __construct(array $address)
    {
        $this->payload = $this->makePayload($address);

        $this->authenticate();
    }

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

    /**
     * Getting mirn & nmi
     *
     * @param array|null $address
     *
     * @return array{mirn: ?numeric, mirn_score: string, nmi: ?numeric, nmi_score: string, suggested_nmi: ?string}
     */
    public function searchAddress(?array $address = null): array
    {
        if ($address) {
            $this->payload = $this->makePayload($address);
        }

        $result = [
            'mirn' => null,
            'mirn_score' => '',
            'nmi' => null,
            'suggested_nmi' => null,
            'nmi_score' => '',
        ];



        try {
            Log::info('fast connect payload', $this->payload);
            $response = Http::retry(2)->timeout(150)->withHeaders([

                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->accessToken,
            ])->
            withBody(json_encode($this->payload), 'application/json')
                ->post(config('fastconnect.root_url') . \config('fastconnect.search_nmi_mirn_uri')); //CHANGE
            // ->post("https://api.fastconnect.net.au/api/datafind/address");

            $responseDecoded = json_decode($response->body(), true);

            return $this->parseValues($responseDecoded);
        } catch (Exception $e) {
            $error = $e instanceof RequestException ? "HTTP_ERROR_" . $e->getResponse()->getStatusCode() : 'ERROR';
            $result['mirn_score'] = $error;
            $result['nmi_score'] = $error;

            Log::error("FastConnectService: ", [
                "mgs" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return $result;
        }
    }

    /**
     * Making payload body  for FC address searcing
     *
     * @param array $address
     *
     * @return array
     */
    public function makePayload(array $address = []): array
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
            'WA' => 'Western Australia'
        ];
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

    /**
     * Parsing Nmi from response
     *
     * @param array|null $nmis
     *
     * @return array
     */
    private function parseNmi(?array $nmis): array
    {
        $res = [
            'nmi' => null,
            'nmi_score' => FastConnectService::NO_RESULT,
            'suggested_nmi' => null,
        ];
        if (!$nmis || sizeof($nmis) === 0) {
            return $res;
        }

        $exact = [];
        $suggested = [];

        foreach ($nmis as $nmi) {
            if ($this->isActiveNMI($nmi)) {
                $exact[] = $nmi['nmi'];
            }

            if ($this->isSuggestedActiveNMI($nmi)) {
                $suggested[] = $nmi['nmi'];
            }
        }

        info("YAYAYAYAKAKAKA", [$exact, $suggested]);

        if (sizeof($exact) === 0) {
            $res['nmi_score'] = FastConnectService::NO_EXACT_OR_ACTIVE;
            $res['suggested_nmi'] = $suggested[0] ?? null;
            return $res;
        }

        if (sizeof($exact) > 1) {
            $res['nmi_score'] = FastConnectService::MULTIPLE_EXACT;
            return $res;
        }

        $res['nmi_score'] = FastConnectService::EXACT;
        $res['nmi'] = $exact[0];

        return $res;
    }

    /**
     * Checking the nmi is active
     *
     * @param array $nmi
     *
     * @return bool
     */
    private function isActiveNMI(array $nmi): bool
    {
        return isset($nmi['nmi'])
            && ($nmi['match_type'] ?? null) === "EXACT"
            &&  ($nmi['status'] ?? null) === 'ACTIVE';
    }

    /**
     * Checking the nmi is active
     *
     * @param array $nmi
     *
     * @return bool
     */
    private function isSuggestedActiveNMI(array $nmi): bool
    {
        return isset($nmi['nmi'])
            && ($nmi['match_type'] ?? null) === "SUGGESTION"
            && ($nmi['status'] ?? null) === 'ACTIVE'
            && sizeof(($nmi['match_type_enums'] ?? [])) === 1
            && in_array('UNIT_NUMBER', $nmi['match_type_enums']);
    }

    /**
     * Parsing MIRN from response
     *
     * @param array|null $mirns
     *
     * @return array
     */
    private function parseMirn(?array $mirns): array
    {
        $res = [
            'mirn' => null,
            'mirn_score' => FastConnectService::NO_RESULT,
        ];
        if (!$mirns || sizeof($mirns) === 0) {
            return $res;
        }

        $exact = [];

        foreach ($mirns as $mirn) {
            if (isset($mirn['mirn']) && ($mirn['match_type'] ?? null) === "EXACT") {
                $exact[] = $mirn['mirn'];
            }
        }

        if (sizeof($exact) === 0) {
            $res['mirn_score'] = FastConnectService::NO_EXACT_OR_ACTIVE;
            return $res;
        }

        if (sizeof($exact) > 1) {
            $res['mirn_score'] = FastConnectService::MULTIPLE_EXACT;
            return $res;
        }

        $res['mirn_score'] = FastConnectService::EXACT;
        $res['mirn'] = $exact[0];

        return $res;
    }
}
