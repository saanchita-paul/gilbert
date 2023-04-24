<?php

namespace GoogleAds\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

use function App\Services\Genesys\sizeof;

class GenesysConversionDetailsAPI
{
    public const DEFAULT_PAGE_SIZE = 25;
    public int $maxPredicate;

    private string $token;
    private Carbon $startDate;
    private Carbon $endDate;

    public function __construct()
    {
        $this->token = $this->getAccessToken();
        $this->maxPredicate = config('genesys.max_predicate');
    }

    public static function formatPhoneNumber(?string $phoneNumber): ?string
    {
        return preg_replace('/^(?:\+61|0)(4\d{8})$/', 'tel:+61$1', $phoneNumber);
    }

    public function setStartDate(string $startDate)
    {
        $this->startDate = Carbon::parse($startDate);
    }

    public function setEndDate(string $endDate)
    {
        $this->endDate = Carbon::parse($endDate);
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
//            sleep((int) config('genesys.interval'));
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

            if (count($predicates) === $this->maxPredicate) {
                $bodies[] = $this->getBody($predicates);
                $predicates = [];
            }
        }

        $bodies[] = $this->getBody($predicates);

        return $bodies;
    }

    private function getInterval()
    {
        $startInterval = isset($this->startDate) ? $this->startDate->toIso8601String() : Carbon::now()->subWeek()->startOfWeek()->toIso8601String();
        $endInterval = isset($this->endDate) ? $this->endDate->toIso8601String() : Carbon::now()->toIso8601String();

        return "$startInterval/$endInterval";
    }

    private function getBody(array $predicates): array
    {
        return [
            "interval" => $this->getInterval(),
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
            "interval" => $this->getInterval(),
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
        $responses = [];
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
            $checkNextPage = false;
            do {
                $response = $client->request("POST", $url, $options);

                $responseBody = json_decode($response->getBody()->getContents(), true);
                $responses[] = $responseBody;
                $checkNextPage = array_key_exists('conversations', $responseBody) &&
                                    count($responseBody['conversations']) >= $body['paging']['pageSize'];
                if ($checkNextPage) {
                    $this->setNextPageBody($body);
                    $options['json'] = $body;
                }
            } while ($checkNextPage);
        } catch (\Exception $e) {
            \Log::error("GetConversationDetailService: api call failed: {$e->getMessage()}");
        }
        return $responses;
    }

    private function setNextPageBody(array &$body)
    {
        $body['paging']['pageNumber'] += 1;
    }

    public function test(): array
    {
        // $testPhones = $this->dummyPhones();
        $testPhones = array_slice($this->dummyPhones(), 0, 10);
        $bodies = $this->buildRequestsBody($testPhones);
        $results = [];

        foreach ($bodies as $body) {
            $responseBody = $this->run($body);
            $results = array_merge($results, $responseBody);
        }
        info($results);
        return $results;
    }

    private function dummyPhones(): array
    {
        // tested 201 phone numbers all at once working
        // latest from 12/04/2023
        return [
            'tel:+61439669169',
            'tel:+61402487835',
            'tel:+61408953821',
            'tel:+61468304456',
            'tel:+61456006949',
            'tel:+61457871770',
            'tel:+61457275859',
            'tel:+61414182057',
            'tel:+61470634859',
            'tel:+61423982810',
            'tel:+61410056314',
            'tel:+61415053068',
            'tel:+61423798015',
            'tel:+61456352847',
            'tel:+61424088241',
            'tel:+61404250403',
            'tel:+61423084982',
            'tel:+61414815030',
            'tel:+61400419966',
            'tel:+61450157234',
            'tel:+61411858473',
            'tel:+61416438038',
            'tel:+61411858473',
            'tel:+61422914562',
            'tel:+61432101572',
            'tel:+61451454097',
            'tel:+61406440761',
            'tel:+61431526114',
            'tel:+61418550990',
            'tel:+61484240602',
            'tel:+61404417780',
            'tel:+61402492325',
            'tel:+61434247131',
            'tel:+61490339646',
            'tel:+61460642990',
            'tel:+61431526025',
            'tel:+61432073132',
            'tel:+61479116922',
            'tel:+61466835114',
            'tel:+61478965567',
            'tel:+61414328794',
            'tel:+61484850431',
            'tel:+61404211094',
            'tel:+61490339646',
            'tel:+61487555384',
            'tel:+61436312821',
            'tel:+61482600001',
            'tel:+61433426651',
            'tel:+61424067577',
            'tel:+61405948571',
            'tel:+61411588480',
            'tel:+61448840940',
            'tel:+61470204624',
            'tel:+61478559883',
            'tel:+61412513855',
            'tel:+61447668681',
            'tel:+61404550000',
            'tel:+61413487092',
            'tel:+61484281230',
            'tel:+61499341932',
            'tel:+61409988656',
            'tel:+61407055030',
            'tel:+61478100936',
            'tel:+61478137477',
            'tel:+61452013211',
            'tel:+61412338191',
            'tel:+61430809033',
            'tel:+61484037301',
            'tel:+61484037301',
            'tel:+61401806808',
            'tel:+61401785029',
            'tel:+61493326319',
            'tel:+61409103702',
            'tel:+61450860450',
            'tel:+61422091408',
            'tel:+61490421682',
            'tel:+61457943842',
            'tel:+61403645073',
            'tel:+61480103010',
            'tel:+61480103010',
            'tel:+61401864565',
            'tel:+61412299276',
            'tel:+61413299066',
            'tel:+61490421682',
            'tel:+61482468376',
            'tel:+61451140716',
            'tel:+61421353153',
            'tel:+61474869074',
            'tel:+61421036246',
            'tel:+61417822100',
            'tel:+61423113844',
            'tel:+61404740331',
            'tel:+61499582208',
            'tel:+61400377037',
            'tel:+61481219726',
            'tel:+61406004012',
            'tel:+61412811720',
            'tel:+61477543341',
            'tel:+61478240064',
            'tel:+61458451999',
            'tel:+61423844789',
            'tel:+61408802442',
            'tel:+61466235166',
            'tel:+61400377037',
            'tel:+61401383228',
            'tel:+61434508258',
            'tel:+61433592324',
            'tel:+61438939780',
            'tel:+61452400227',
            'tel:+61456852476',
            'tel:+61417132364',
            'tel:+61451362410',
            'tel:+61400294568',
            'tel:+61414744997',
            'tel:+61419585107',
            'tel:+61407877123',
            'tel:+61410129883',
            'tel:+61452565485',
            'tel:+61400967170',
            'tel:+61427556002',
            'tel:+61478007588',
            'tel:+61423530423',
            'tel:+61405795382',
            'tel:+61419203194',
            'tel:+61434493216',
            'tel:+61469783931',
            'tel:+61425777373',
            'tel:+61408107306',
            'tel:+61405004035',
            'tel:+61449724300',
            'tel:+61478187491',
            'tel:+61452419898',
            'tel:+61479037196',
            'tel:+61466375433',
            'tel:+61427118580',
            'tel:+61455785222',
            'tel:+61458018950',
            'tel:+61431822719',
            'tel:+61452078786',
            'tel:+61412883620',
            'tel:+61431822719',
            'tel:+61401497745',
            'tel:+61470252783',
            'tel:+61421378927',
            'tel:+61414919172',
            'tel:+61401506800',
            'tel:+61406486488',
            'tel:+61459957257',
            'tel:+61405778500',
            'tel:+61402488411',
            'tel:+61469126498',
            'tel:+61420573341',
            'tel:+61416621067',
            'tel:+61421178476',
            'tel:+61421237317',
            'tel:+61421660026',
            'tel:+61466092420',
            'tel:+61414746690',
            'tel:+61432582159',
            'tel:+61420651655',
            'tel:+61403234249',
            'tel:+61472820587',
            'tel:+61426024059',
            'tel:+61401861671',
            'tel:+61428755268',
            'tel:+61422321625',
            'tel:+61449825996',
            'tel:+61490247998',
            'tel:+61418709217',
            'tel:+61487766759',
            'tel:+61412879468',
            'tel:+61458986215',
            'tel:+61459407615',
            'tel:+61413435007',
            'tel:+61432274698',
            'tel:+61429727426',
            'tel:+61411621213',
            'tel:+61411381613',
            'tel:+61466895831',
            'tel:+61418931395',
            'tel:+61447349534',
            'tel:+61432661519',
            'tel:+61493396846',
            'tel:+61451243244',
            'tel:+61450935112',
            'tel:+61484932063',
            'tel:+61415890109',
            'tel:+61403466242',
            'tel:+61405157285',
            'tel:+61411602583',
            'tel:+61418475898',
            'tel:+61493402569',
            'tel:+61447551186',
            'tel:+61481946230',
            'tel:+61478729502',
            'tel:+61432633847',
            'tel:+61423720807',
            'tel:+61405459866',
            'tel:+61497705349',
            'tel:+61459350352',
            'tel:+61431732348',
        ];
    }
}
