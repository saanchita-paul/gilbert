<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Query;
use GraphQL\Variable;

class GetSalesRequestStaus
{
    private $accessToken;




    public function __construct()
    {
        $this->accessToken = (new GetAccessToken())->getAccessToken();
    }


    public function getSalesStatusByDateRange()
        {

            $fromDate = ConnectionApplication::query()
                ->where('status','=',ConnectionApplication::STATUS_SUBMITTED)
                ->orderBy('created_at',)
                ->pluck('created_at')
                ->first()->format('Y-m-d')
            ;

            //this is for testing purposes
            $fromDate = '2020-10-10';

            $todate = Carbon::now()->format('Y-m-d');

               $da = ['data' => [
                    'vendorCode'=> "HD2",
                    'submittedFrom'=> $fromDate,
                    'submittedTo'=> $todate,
                    'pageable'=> [
                        'size'=>50,
                        'page'=>1
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
            $results = $client->runQuery($gql, true, $da );
            return $this->manageConnectionList($results->getResponseBody());


        }

        public function manageConnectionList($results)
        {
            $data = (json_decode($results));
            $quotes = $data->data->getVendorSaleStatusByDateRange->content;




            foreach ($quotes as $item) {
                foreach ($item->quotes as $quote)
                {
                    $status = null;
                    $status = null;
                    if($quote->status == 'REJECTED')
                    {
                        $status = ConnectionApplication::STATUS_REJECTED;
                    }

                    if($quote->status == 'PROCESSING')
                    {
                        $status = ConnectionApplication::STATUS_EA_PROCESSING;
                    }

                }
                $connection = ConnectionApplication::where('ea_sales_id', $item->id)->first();
                if(!is_null($connection))
                {
                    $connection->update(['status'=>$status]);
                }

            }
            die();
        }
}
