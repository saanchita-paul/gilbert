<?php

namespace App\Services\Genesys;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Carbon;

class GetConversationDetailService
{
    public const DEFAULT_PAGE_SIZE = 25;

    private string $token;

    public function __construct()
    {
        $this->token = $this->getAccessToken();
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

    /**
     * Get Conversation Details filter by customer phone number
     */
    public function filterByCustomerPhone()
    {
        $body = [
            "interval" => "2023-04-03T00:00:00/2023-04-06T23:59:59", // startDateEndTime/startEndDateTime
            "paging" => [
                "pageSize" => self::DEFAULT_PAGE_SIZE,
                "pageNumber" => 1
            ],
            "segmentFilters" => [
                [
                    "predicates" => [
                        [
                            "dimension" => "ani",
                            "operator" => "matches",
                            "value" => "tel:+61457275859"
                        ],
                        [
                            "dimension" => "ani",
                            "operator" => "matches",
                            "value" => "tel:+61414182057"
                        ],
                    ],
                    "type" => "or"
                ]
            ],
        ];

        return $this->run($body);
    }

    /**
     * Get Conversation Details filter by HOOD number for testing bulk response data
     */
    public function filterByHoodPhone()
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

    private function run(array $body)
    {
        $token = $this->token;
        $url = config('genesys.base_url') . config('genesys.endpoints.detail_conversation');

        $headers = [
            "authorization" => "Bearer $token",
            "content-type" => "application/json",
            "accept" => "application/json"
        ];

        $options = [
            "json" => $body
        ];

        $client = new Client([
            'headers' => $headers
        ]);

        try {
            $response = $client->request("POST", $url, $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return $this->formatResponseData($data);
    }

    private function formatResponseData(array $data)
    {
        if (!array_key_exists('conversations', $data)) {
            return [];
        }

        $return = [];
        $conversations = $data['conversations'];
        $return['total'] = $data['totalHits'];
        $return['count'] = count($conversations);

        foreach ($conversations as $conversation) {
            try {
                $participant = $conversation['participants'][0];
                $session = $participant['sessions'][0];
                $ani = $session['ani'];
                $dnis = $session['dnis'];
                $start = $conversation['conversationStart'];
                $end = $conversation['conversationEnd'];
                $return['data'][] = [
                    'ani' => $ani,
                    'dnis' => $dnis,
                    'start' => $start,
                    'end' => $end
                ];
            } catch (\Exception $e) {
                \Log::warning($e->getMessage());
                continue;
            }
        }

        return $return;
    }
}
