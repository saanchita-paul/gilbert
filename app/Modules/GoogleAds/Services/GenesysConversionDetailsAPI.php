<?php

namespace GoogleAds\Services;

use GuzzleHttp\Client;

use function App\Services\Genesys\sizeof;

class GenesysConversionDetailsAPI
{
    public const DEFAULT_PAGE_SIZE = 25;
    public int $maxPredicate;

    private string $token;


    public function __construct()
    {
        $this->token = $this->getAccessToken();
        $this->maxPredicate = config('genesys.max_predicate');
    }

    public static function formatPhoneNumber(?string $phoneNumber): ?string
    {
        return preg_replace('/^(?:\+61|0)(4\d{8})$/', 'tel:+61$1', $phoneNumber);
    }

    /**
     * Get Conversation Details filter by customer phone number
     */
    public function searchByPhones(array $phones): array
    {
        $bodies = $this->buildRequestsBody($phones);
        $results = [];

        foreach ($bodies as $body) {
            $results = array_merge($results, $this->run($body));
        }

        return $results;
    }

    public function isValidCallerId(?string $phone): bool
    {
        return preg_match('/^tel:\+614\d{8}$/', $phone) === 1;
    }
    /**
     * Formatting payload with phone number
     */
    private function buildRequestsBody(array $phones): array
    {
        $predicates = [];
        $bodies = [];

        foreach ($phones as $phone) {

            if(!$this->isValidCallerId($phone)) {
                continue;
            }

            $predicates[] = [
                "dimension" => "ani",
                "operator" => "matches",
                "value" => $phone
            ];

            if (sizeof($predicates) === $this->maxPredicate) {
                $bodies[] = $this->getBody($predicates);
                $predicates = [];
            }
        }

        $bodies[] = $this->getBody($predicates);

        return $bodies;
    }

    private function getBody(array $predicates): array
    {
        return [
            "interval" => "2023-03-07T00:00:00/2023-04-06T23:59:59", // startDateEndTime/startEndDateTime
            "paging" => [
                "pageSize" => self::DEFAULT_PAGE_SIZE,
                "pageNumber" => 1
            ],
            "segmentFilters" => [
                [
                    "predicates" => $predicates,
                    "type" => "or"
                ]
            ],
        ];
    }

    /**
     * Get Conversation Details filter by call center number for testing bulk response data
     */
    public function filterByCallCenterPhone()
    {
        $predicates = [];
        foreach (config('genesys.call_center_numbers') as $num) {
            $predicates[] = [
                "dimension" => "dnis",
                "operator" => "matches",
                "value" => "tel:+$num"
            ];
        }

        $body = [
            "interval" => "2023-04-06T00:00:00/2023-04-06T23:59:59",
            "paging" => [
                "pageSize" => 30,
                "pageNumber" => 1
            ],
            "segmentFilters" => [
                [
                    "predicates" => $predicates,
                    "type" => "or"
                ]
            ],
        ];

        return $this->run($body);
    }



    private function getAccessToken()
    {
        $url = config('genesys.token_url');
        $query = [
            "grant_type" => "client_credentials",
            "client_id" => config('genesys.client_id'),
            "client_secret" => config('genesys.client_secret')
        ];
        $options = [
            "query" => $query
        ];

        $client = new Client();

        $response = $client->request("POST", $url, $options);

        $data = json_decode($response->getBody()->getContents(), true);

        $token = $data["access_token"];

        return $token;
    }

    private function run(array $body)
    {
        $url = config('genesys.base_url') . config('genesys.endpoints.detail_conversation');
        $client = new Client();

        $headers = [
            "authorization" => "Bearer {$this->token}",
            "content-type" => "application/json",
            "accept" => "application/json"
        ];

        $options = [
            'headers' => $headers,
            "json" => $body
        ];


        try {
            $response = $client->request("POST", $url, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::error("GetConversationDetailService: api call failed: {$e->getMessage()}");
            return [];
        }
    }
}
