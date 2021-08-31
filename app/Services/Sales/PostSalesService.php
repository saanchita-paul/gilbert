<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Mutation;
use GraphQL\Variable;

class PostSalesService
{

    private  $connection;
    private $identification;
    private $accessToken;
    public function __construct(int $id)
    {
        $this->connection = ConnectionApplication::find(23);
        $this->identification = $this->connection->identification;
        $this->accessToken = (new GetAccessToken())->getAccessToken();
    }

    public function postToEa()
    {

        $id = $this->getId();
        $vendorCode= "HD2";
        $version = "1";
        $saleDate =  "2021-08-31T10:45:00Z";
        $customerType =  "RES";
        $transactionType = "ENE";
        $premiseRelationship= "TENANT";
        $customer = [
            'title'=> $this->connection->title,
            'firstName'=> $this->connection->first_name,
            'lastName'=> $this->connection->last_name,
            'emailAddress'=> $this->connection->email,
            'dateOfBirth'=>  $this->connection->dob,
            'premiseRelationship'=> $premiseRelationship,
            'phone'=> [
                [
                    'type'=> 'MOBILE',
                    'number'=>$this->connection->phone
                ]
            ],
            'preferredContactMethod' => 'EMAIL',
            'identification'=> $this->getIdentification()
        ];

        $energisation = [
            'connectionDate'=>(new Carbon( $this->connection->moving_date))->format('Y-m-d'),
            'accessDetails'=> "frfsef",
            'renovationsSinceDeenergisation'=> false,
            'renovationsInProgressOrPlanned'=> false,
            'afterHoursServiceOrder'=> false,
        ];


        $premise = [
            'mirn'=> "5321303906",
            'address'=> [
                'unitNumber'=> "7",
                'streetNumber'=> "61",
                'streetName'=> "61 MALTRAVERS RD",
                'streetType'=> "ST",
                'suburb'=> "IVANHOE EAST",
                'state'=> "VIC",
                'postcode'=> "3079",
            ],
            'solarDetails'=> [
                'solarPower'=> $this->connection->has_solar?true:false,
            ]
        ];



        $offers = [
            [
                'fuel'=> 'ELE',
                'planId'=> "RSOT-EV",
                'sourceCode'=> "Basic",
            ]
        ];
        $mailingAddressType = 'POSTAL';

        $postalMailingAddress = [
            "postalDeliveryNumber"=> "1000",
            "postalDeliveryType"=> "PO_BOX",
            "suburb"=> "Melbourne",
            "state"=> "VIC",
            "postcode"=> "3001",
        ];


        $billDeliveryMethod = $this->connection->is_email_billing?'EMAIL':'POST';
        $lifeSupport = $this->connection->has_life_support?true:false;

        $eaData = [
            "id"=> $id,
    "vendorCode"=> "HD2",
    "version"=> "1",
    "saleDate"=> "2021-08-30T10:45:00Z",
    "customerType"=> "RES",
    "transactionType"=> "ENE",
    "customer"=> $customer,
    "energisation"=>$energisation
        ,
    "premise"=>$premise,

    "offers"=>
        [
            [
                "fuel"=> "ELE",
                "planId"=> "RSOT-EV",
                "sourceCode"=> "Basic",
            ]
    ],
    "mailingAddressType"=> $mailingAddressType,
    "postalMailingAddress"=> $postalMailingAddress,
    "billDeliveryMethod"=> $billDeliveryMethod,
  	"lifeSupport"=> $lifeSupport,

];


        $variables= [
            'data'=>$eaData
        ];

        try{
            $client = new Client(
                'https://apigw-nonprod.energyaustralia.com.au/graphql',
                ['Authorization' => $this->accessToken]);
                $gql = (new Mutation('submitSale'))
                ->setVariables([new Variable('data', 'VendorSaleRequest!')])
                ->setArguments(['input' => '$data'])
                ->setSelectionSet(
                    [
                        'id
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
                        }'
                    ]
                );
            $results = $client->runQuery($gql, false, $variables );
            return $this->processEaData($results->getResponseBody());
        } catch (\Exception $e)
        {
            \Log::info($e->getMessage());
        }


    }

    public function processEaData($results)
    {

        $data = json_decode($results);
        $submitSallData = $data?->data?->submitSale;
        $quotes = $submitSallData?->quotes;
        $salesId = $submitSallData?->id;

        foreach ($quotes as $quote)
        {
            $status = null;
            if($quote->status == 'REJECTED')
            {
                $status = ConnectionApplication::STATUS_REJECTED;
            }

            if($quote->status == 'ACCEPTED')
            {
                $status = ConnectionApplication::STATUS_ACCEPTED;
            }
            $this->connection->update(['status'=>$status,'ea_sales_id'=> $salesId]);
        }

    }


    public function getIdentification()
    {
        if($this->identification->type == 1)
        {
            return [
                'type'=> "PASSPORT",
                'number'=> $this->identification->card_number,
                'firstName'=> $this->connection->first_name,
                'lastName'=> $this->connection->first_name,
//                'stateOfIssue'=> $this->identification->state,
                'expiry'=> $this->identification->expire_date,
//                'countryOfIssue'=> $this->identification->country
                'countryOfIssue'=> "AUS"
            ];
        }
        else if($this->identification->type ==2)
        {
            return [
                'type'=> "MEDICARE",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->first_name,
                'stateOfIssue'=> $this->identification->state,
                'expiry'=> $this->identification->expire_date,
                'medicareReferenceNumber'=> $this->identification->special_number,
                'medicareCardColour'=> $this->identification->card_color,
            ];
        }
        else if($this->identification->type ==3)
        {
            return [
                'type'=> "DL",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->first_name,
                'stateOfIssue'=> $this->identification->state,
                'expiry'=> $this->identification->expire_date,
            ];
        }
    }

    private function getId()
    {
        return 'HD2'.$this->connection->id.time();
    }

}
