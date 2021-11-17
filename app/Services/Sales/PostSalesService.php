<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Mutation;
use GraphQL\Variable;
use Illuminate\Support\Facades\Log;

class PostSalesService
{

    private  $connection;
    private $identification;
    private $accessToken;
    public function __construct(int $id)
    {
        $this->connection = ConnectionApplication::find($id);
        $this->identification = $this->connection->identification;
        $this->accessToken = (new GetAccessToken())->getAccessToken();
    }

    public function postToEa()
    {

        $id = $this->getId();
        $vendorCode= "HD2";
        $version = "1";
        $saleDate =  "2021-21-07T10:45:00Z";
        $saleDate = (new Carbon($this->connection->updated_at))->toIso8601String();
        $customerType =  "RES";
        $transactionType = "ENE";
        $premiseRelationship= $this->connection->tenancy_type == 1?"TENANT":"OWNER";
        $customer = [
            'title'=> strtoupper($this->connection->title),
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
            'mirn'=> $this->connection->mirn,
            'nmi'=> $this->connection->nmi,

            'address'=> [
                'unitNumber'=> $this->connection->address_unit,
                'streetNumber'=> $this->connection->street_number,
                'streetName'=> $this->connection->street_address,
                'streetType'=> "ST",
                'suburb'=> $this->connection->city,
                'state'=> $this->stateMap($this->connection->state),
                'postcode'=> $this->connection->postcode,
            ],
            'solarDetails'=> [
                'solarPower'=> $this->connection->has_solar?true:false,
            ]
        ];

        $premise1 = [
            'mirn'=> $this->connection->mirn,

            'address'=> [
                'unitNumber'=> $this->connection->address_unit,
                'streetNumber'=> "61",
                'streetName'=> $this->connection->street_address,
                'streetType'=> "ST",
                'suburb'=> $this->connection->city,
                'state'=> $this->connection->state,
                'postcode'=> $this->connection->postcode,
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
        $mailingAddressType = 'STREET';

        $streetMailingAddress = [
            'unitNumber'=> $this->connection->address_unit,
            'streetNumber'=> "61",
            'streetName'=> $this->connection->street_address,
            'streetType'=> "ST",
            'suburb'=> $this->connection->city,
            'state'=> $this->stateMap($this->connection->state),
            'postcode'=> $this->connection->postcode,
        ];


        $billDeliveryMethod = $this->connection->is_email_billing?'EMAIL':'POST';
        $lifeSupport = $this->connection->has_life_support?true:false;

        $eaData = [
            "id"=> $id,
            "vendorCode"=> "HD2",
            "version"=> "1",
            "saleDate"=> "2021-11-29T10:45:00Z",
            "customerType"=> "RES",
            "transactionType"=> "ENE",
            "customer"=> $customer,
            "energisation"=>$energisation,
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
        "streetMailingAddress"=> $streetMailingAddress,
        "billDeliveryMethod"=> $billDeliveryMethod,
        "lifeSupport"=> $lifeSupport,
        ];

        info("START EA Data");
        info(json_encode($eaData));
        info("END EA Data");
        $variables= [
            'data'=>$eaData
        ];

        try{
            $url = env('EA_SALES_URL','https://apigw-nonprod.energyaustralia.com.au/graphql');
            $client = new Client(
                $url,
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
            return $e->getMessage();
        }


    }

    public function processEaData($results)
    {
        $data = json_decode($results);

        $submitSallData = $data?->data?->submitSale;
        $quotes = $submitSallData?->quotes;
        $salesId = $submitSallData?->id;


        info("START EA Data Response");
        info(json_encode($data));
        info("END EA Data Response");


        foreach ($quotes as $quote)
        {
            $status = null;
            if($quote->status == 'REJECTED')
            {
                $status = ConnectionApplication::STATUS_REJECTED;
            }

            if($quote->status == 'PROCESSING')
            {
                $status = ConnectionApplication::STATUS_EA_PROCESSINF;
            }
            $this->connection->update(['status'=>$status,'ea_sales_id'=> $salesId,'assigned_to'=> null]);
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
                'expiry'=> $this->identification->expire_date,
                'countryOfIssue'=> "AUS"
            ];
        }
        else if($this->identification->type ==3)
        {
            return [
                'type'=> "MEDICARE",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->first_name,
                'expiry'=> $this->identification->expire_date,
                'medicareReferenceNumber'=> $this->identification->special_number,
                'medicareCardColour'=> strtoupper($this->identification->card_color),
            ];
        }
        else if($this->identification->type ==2)
        {
            Log::info($this->identification);
            return [
                'type'=> "DL",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->first_name,
                'stateOfIssue'=> $this->stateMap($this->identification->state),
                'expiry'=> $this->identification->expire_date,
            ];
        }
    }

    private function getId()
    {
        return 'HD2'.$this->connection->id.time();
    }

    private function stateMap($state)
    {
        $stateList = ['New South Wales'=>'NSW','Victoria'=>'VIC','Queensland'=>'QLD',
            'South Australia'=>'SA','Northern Territory'=>'NT','TAS'=>'Tasmania','ACT'=>'Australian Capital Territory'];
        if(array_key_exists($state, $stateList))
        {
            return $stateList[$state];
        }
        return $state;

    }

}
