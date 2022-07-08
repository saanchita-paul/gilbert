<?php


namespace App\Services\Sales;


use App\Jobs\CheckSaleApiLeadData;
use App\Models\APILog;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Office;
use App\Services\Logger\LogSalesService;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Query;
use GraphQL\Variable;
use Illuminate\Support\Facades\Log;

class GetSalesRequestStaus
{
    use SalesResponseHandle;

    private $accessToken;

    public function __construct()
    {
    }

    /**
     * @deprecated
     */
    public function getSalesStatusByDateRange()
    {
        $fromDate = ConnectionApplication::query()
            ->where('status', '=', ConnectionApplication::STATUS_SUBMITTED)
            ->orderBy('created_at',)
            ->pluck('created_at')
            ->first()->format('Y-m-d');

        //this is for testing purposes
        $fromDate = '2020-10-10';

        $todate = Carbon::now()->format('Y-m-d');

        $da = ['data' =>
            [
                'getVendorCode' => "HD2",
                'submittedFrom' => $fromDate,
                'submittedTo' => $todate,
                'pageable' => [
                    'size' => 50,
                    'page' => 1
                ],
            ]

        ];

        $gql = (new Query('getVendorSaleStatusByDateRange'))
            ->setVariables([new Variable('data', 'StatusByDateRangeInput!')])
            ->setArguments(['input' => '$data'])
            ->setSelectionSet(
                [
                    'totalPages
                    totalElements
                    numberOfElements
                    pageable {
                           paged
                           }
                    first
                    last
                    size
                    content {
                        id
                        version
                        quotes {
                                id
                                status
                                fuel
                                lastUpdated
                                        rejectionReasons {
                                          code
                                          detail
                                        }
                                }
                            }
                number
                empty
                '
                ]
            );


        $client = new Client(
            'https://apigw-nonprod.energyaustralia.com.au/graphql',
            ['Authorization' => $this->accessToken]);
        $results = $client->runQuery($gql, true, $da);
        return $this->manageConnectionList($results->getResponseBody());
    }

    /**
     * @deprecated
     */
    public function manageConnectionList($results)
    {
        $data = (json_decode($results));
        $quotes = $data->data->getVendorSaleStatusByDateRange->content;
        foreach ($quotes as $item) {
            foreach ($item->quotes as $quote) {
                $status = null;
                $status = null;
                if ($quote->status == 'REJECTED') {
                    $status = ConnectionApplication::STATUS_REJECTED;
                }

                if ($quote->status == 'PROCESSING') {
                    $status = ConnectionApplication::STATUS_EA_PROCESSINF;
                }


            }
            $connection = ConnectionApplication::where('ea_sales_id', $item->id)->first();
            if (!is_null($connection)) {
                $connection->update(['status' => $status]);
            }
        }
    }


    public function getSalesStatus($salesId, $leadId)
    {
        /** @var Office $office */
        $office = ConnectionApplication::with('office')->where('id', $leadId)->first()->office;
        $logSalesService = new LogSalesService();
        $da = ['data' =>
            [
                'vendorCode' => $office->getVendorCode(),
                'id' => $salesId
            ]

        ];

        $gql = (new Query('getVendorSaleStatusById'))
            ->setVariables([new Variable('data', 'StatusByIdInput!')])
            ->setArguments(['input' => '$data'])
            ->setSelectionSet(
                [
                    'version
                    quotes {
                    id
    status
    fuel
    lastUpdated
    rejectionReasons {
      code
      detail
    }
  }'
                ]
            );

        $url = env('EA_SALES_URL', 'https://apigw-nonprod.energyaustralia.com.au/graphql');
        $XEAEnv = config('ea.x_ea_env');
        $header = ['Authorization' => $this->getAccessToken($leadId), 'X-EA-Env' => $XEAEnv];
        $client = new Client(
            $url,
            $header
        );

        //call logger api to save log

        $loggerResponse = $logSalesService->createSalesLog(
            $url,
            APILog::API_SALES_API_GET_STATUS,
            'POST',
            json_encode($da),
            json_encode($header)
        );
        $results = $client->runQuery($gql, true, $da);


        //update logger after response from graghql
        $logSalesService->updateSalesLog($loggerResponse->key,
            json_encode($results->getData()),
            json_encode([]),
            200
        );

        $this->processEaData($results->getResponseBody(), $leadId);

    }

    /**
     * @throws \Exception
     */
    public function processEaData($results, $leadId)
    {
        Log::info(json_encode($results));

        $data = json_decode($results);

        $submitSallData = $data?->data?->getVendorSaleStatusById;

        Log::info(json_encode($submitSallData));
        $quotes = $submitSallData?->quotes ?? [];
        $this->handleResponse($quotes, $leadId);


    }

    public function fetchAllSubmittedLead()
    {
        $services = ConnectionService::query()
            ->with('connectionApplication')
            ->where('status', ConnectionService::STATUS_ENERGY_SUBMIT)
            ->where('provider_name', '=', ConnectionService::PROVIDER_EA)
            ->whereIn('service_type', [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY])
            ->whereNotNull('lead_reference')
            ->distinct()
            ->get()
            ->unique('lead_reference');

        foreach ($services as $service) {

            CheckSaleApiLeadData::dispatch($service->connection_application_id, $service->lead_reference);
        }

    }

    /**
     * @return string
     */
    public function getAccessToken(int $leadId): string
    {
        return (new GetAccessToken())->getAccessToken($leadId);
    }
}
