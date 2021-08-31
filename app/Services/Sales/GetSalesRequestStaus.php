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

    private  $arr = [
    id => "HD2323sfs432154567",
    vendorCode => "HD2",
    version => "1",
    saleDate => "2021-08-30T10 =>45 =>00Z",
    customerType => "RES",
    transactionType => "ENE",
    customer => [
        title => "MR",
        firstName => "test",
        lastName => "test",
        emailAddress => "test@example.com",
        dateOfBirth => "1980-01-01",
        phone => [
            [
                type => "MOBILE",
                number => "0412345678"
            ],
],
preferredContactMethod => "EMAIL",
        identification => [
    type => "PASSPORT",
            number => "R0986754",
            firstName => "abcdefg",
            lastName => "abcdefg",
            expiry => "2024-01-01",
            countryOfIssue => "Australia",
        ],
    ],

    energisation => [
    connectionDate => "2020-03-02",
        accessDetails => "frfsef",
        renovationsSinceDeenergisation => false,
        renovationsInProgressOrPlanned => false,
        afterHoursServiceOrder => false ,
    ],
    premise => [
    nmi => "1234567890",
        mirn => "1234567890",
        address => [
        unitNumber => "2a",
            streetNumber => "21",
            streetName => "test1",
            streetType => "ST",
            suburb => "Melbourne",
            state => "VIC",
            postcode => "3000",
        ],
        solarDetails => [
        solarPower => false,
        ],
    ],
    offers => [
        [
            fuel => 'ELE',
            planId => "RSOT-EV",
            sourceCode => "Basic",

        ],
        [
            fuel => 'GAS',
            planId => "RSOT-GV",
            sourceCode => "Basic",
        ],
    ],
    mailingAddressType => "POSTAL",
    postalMailingAddress => [
    postalDeliveryNumber => "1000",
        postalDeliveryType => "PO_BOX",
        suburb => "Melbourne",
        state => "VIC",
        postcode => "3001",

    ],
    billDeliveryMethod => "POST",
  	lifeSupport => false
];


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

            $fromDate = '2020-10-10';

            $todate = Carbon::now()->format('Y-m-d');

            dump($fromDate);
            dump($todate);

            $variables =[
                'data'=> [
                    'vendorCode'=> "HD2",
                    'submittedFrom'=> $fromDate,
                    'submittedTo'=> $todate,
                    'pageable'=> [
                        'size'=>10,
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
            $results = $client->runQuery($gql, true, $this->arr );
            dump($this->arr);
            return $this->manageConnectionList($results->getResults());


        }

        public function manageConnectionList($results)
        {
            dump($results?->data?->getVendorSaleStatusByDateRange?->content);
        }
}
