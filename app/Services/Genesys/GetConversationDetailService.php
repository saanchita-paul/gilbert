<?php

namespace App\Services\Genesys;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Carbon;

class GetConversationDetailService
{
    public const DEFAULT_PAGE_SIZE = 25;

    private string $token;
    private array $output;
    private array $aniMap;

    public function __construct()
    {
        $this->token = $this->getAccessToken();
    }

    public static function formatPhoneNumber(?string $phoneNumber): ?string
    {
        return preg_replace('/^(?:\+61|0)(4\d{8})$/', 'tel:+61$1', $phoneNumber);
    }

    /**
     * Get Conversation Details filter by customer phone number
     */


    public function searchByPhones(array $apps)
    {
        $body = $this->buildPayloadWithPhones($apps);

        return $this->run($body);
    }

    private function buildPayloadWithPhones(array $apps): array
    {
        foreach ($apps as $app) {
            $phone = $this->formatPhoneNumber($app['phone']);
            $this->aniMap[$phone] = [
                'caller_id' => $phone,
                'connection_application_id' => $app['id']
            ];
            $predicates[] = [
                "dimension" => "ani",
                "operator" => "matches",
                "value" => $phone
            ];
        }
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
            $data = json_decode($response->getBody()->getContents(), true);
            return $this->formatResponseData($data);
        } catch (\Exception $e) {
            \Log::error("GetConversationDetailService: api call failed: {$e->getMessage()}");
            return [];
        }
    }

    private function formatResponseData(array $data): array
    {
        if (!array_key_exists('conversations', $data)) {
            return [];
        }

        $return = [];
        $conversations = $data['conversations'];

        foreach ($conversations as $conv) {
            try {
                if (!$session = $conv['participants'][0]['sessions'][0] ?? null) {
                    throw new \Exception("Session not found: conv ID: {$conv['conversationId']}");
                }

                $ani = $session['ani'] ?? null;

                $return[] = [
                    'caller_id' => $ani,
                    'call_start_at' => $conv['conversationStart'] ?? null,
                    'call_send_at' => $conv['conversationEnd'] ?? null,
                ];
            } catch (\Exception $e) {
                \Log::error('GetConversationDetailService.formatResponseData: ' . $e->getMessage());
                continue;
            }
        }

        return $return;
    }
}
