<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Mutation;
use GraphQL\Variable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostSalesService
{

    private $connection;
    private $identification;
    private $accessToken;
    private $chatbotUri;
    private $gasSourceCode;
    private $eleSourceCode;
    private $planType;
    public function __construct(int $id)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->connection = ConnectionApplication::with('connectionServices')->where('id', $id)->firstOrFail();
        $this->identification = $this->connection->identification;
        $this->accessToken = (new GetAccessToken())->getAccessToken();
        $planType = $this->getPlanType();
        $this->eleSourceCode = $this->getElectricitySourceCode($planType, $this->connection->state);
        $this->gasSourceCode = $this->getGasSourceCode($planType, $this->connection->state);

    }

    public function getPlanType()
    {
        foreach ($this->connection->connectionServices as $service)
        {
            if($service->provider_name === 'ea') {
                return $service->plan_type;
            }
        }
        return throw new \Exception('[PostSalesService:getPlanType] plan type not found');
    }

    public function postToEa()
    {

        $id = $this->getId();
        $vendorCode= "HD2";
        $version = "1";
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
                    'type'=> $this->connection->phone_type == 1?'MOBILE': 'HOME',
                    'number'=>$this->connection->phone_type == 1? $this->connection->phone:  $this->connection->homephone
                ]
            ],
            'preferredContactMethod' => 'EMAIL',
            'identification'=> $this->getIdentification()
        ];
        $streetType = self::getStreetType($this->connection->street_name);

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
                'streetType'=> $streetType,
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
                'streetNumber'=> $this->connection->street_number,
                'streetName'=> $this->connection->street_address,
                'streetType'=> $streetType,
                'suburb'=> $this->connection->city,
                'state'=> $this->connection->state,
                'postcode'=> $this->connection->postcode,
            ],
            'solarDetails'=> [
                'solarPower'=> $this->connection->has_solar?true:false,
            ]
        ];



        $offers = $this->prepareOffer();
        $mailingAddressType = 'STREET';

        $streetMailingAddress = [
            'unitNumber'=> $this->connection->address_unit,
            'streetNumber'=> $this->connection->street_number,
            'streetName'=> $this->connection->street_address,
            'streetType'=> $streetType,
            'suburb'=> $this->connection->city,
            'state'=> $this->stateMap($this->connection->state),
            'postcode'=> $this->connection->postcode,
        ];


        $billDeliveryMethod = $this->connection->is_email_billing?'EMAIL':'POST';
        $lifeSupport = $this->connection->has_life_support?true:false;

        $eaData = [
            "id"=> $id,
            "vendorCode"=> $vendorCode,
            "version"=> $version,
            "saleDate"=> $saleDate,
            "customerType"=> $customerType,
            "transactionType"=> $transactionType,
            "customer"=> $customer,
            "energisation"=>$energisation,
            "premise"=>$premise,
            "offers"=>$offers,
            "mailingAddressType"=> $mailingAddressType,
            "streetMailingAddress"=> $streetMailingAddress,
            "billDeliveryMethod"=> $billDeliveryMethod,
            "lifeSupport"=> $lifeSupport,
        ];

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


                Log::info('End Sale API Payload');
                Log::info(json_encode($variables));
                Log::info('Start Sale API Payload');
            $results = $client->runQuery($gql, false, $variables );
            return $this->processEaData($results->getResponseBody());

        } catch (\Exception $e)
        {
            Log::info($e->getMessage());
            return $e->getMessage();
        }


    }

    public function processEaData($results)
    {
        Log::info('End Sale API Response');
        Log::info(json_encode($results));
        Log::info('Start Sale API Response');

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

            if($quote->status == 'PROCESSING')
            {
                $status = ConnectionApplication::STATUS_EA_PROCESSINF;
            }
            $this->connection->update(['status'=>$status,'ea_sales_id'=> $salesId,'assigned_to'=> null]);

        }

    }


    public function getIdentification()
    {
        if($this->identification->type === Identification::TYPE_PASSPORT)
        {
            return [
                'type'=> "PASSPORT",
                'number'=> $this->identification->card_number,
                'firstName'=> $this->connection->first_name,
                'lastName'=> $this->connection->last_name,
                'expiry'=> $this->identification->expire_date,
                'countryOfIssue'=> "AUS"
            ];
        }
        else if($this->identification->type === Identification::TYPE_MEDICARE)
        {
            return [
                'type'=> "MEDICARE",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->last_name,
                'expiry'=> $this->identification->expire_date,
                'medicareReferenceNumber'=> $this->identification->special_number,
                'medicareCardColour'=> strtoupper($this->identification->card_color),
            ];
        }
        else if($this->identification->type === Identification::TYPE_DRIVING_LICENCE )
        {
//            Log::info($this->identification);
            return [
                'type'=> "DL",
                'number'=> $this->identification->card_number,
                'firstName'=>$this->connection->first_name,
                'lastName'=> $this->connection->last_name,
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

    private static function getStreetType(string $streetAddress): string
    {
        $data = explode(' ', $streetAddress);
        return $data[sizeof($data) - 1];
    }

    private function getElectricitySourceCode($plan, $state)
    {
//        $plan = ConnectionApplication::PLAN_TYPE_REVERSE_MAPPER[$plan];
        $state = $this->stateMap($state);

        try{
            $response = Http::post($this->chatbotUri.'/api/ele-source-code',['plan'=>$plan,'state'=>$state]);
            if($response->status() == 200) {
//                return json_decode($response->body())->source_code;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage(),[]);
            return  '';
        }

        return  '';

    }

    private function getGasSourceCode($plan, $state)
    {
//        $plan = ConnectionApplication::PLAN_TYPE_REVERSE_MAPPER[$plan];
        $state = $this->stateMap($state);

        try {
            $response = Http::post($this->chatbotUri.'/api/gas-source-code',['plan'=>$plan,'state'=>$state]);
            if($response->status() == 200) {
//                return json_decode($response->body())->source_code;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage(),[]);
            return  '';
        }
        return  '';
    }

    private function prepareOffer()
    {

//        Log::info('show Prepare call is called');
        $plan = $this->getPlanType();
        $state = $this->stateMap( $this->connection->state);
        $gasPlanSourceCode = '';
        $elePlanSourceCode = '';
        $plan_id = '';

        try {
            $response = Http::post($this->chatbotUri.'/api/get-plan-details',['plan'=>$plan,'state'=>$state]);
//            Log::info($response->status());

            if($response->status() == 200) {
                $response = json_decode($response->body());
                $gasPlanSourceCode = $response->gas_source_code;
                $elePlanSourceCode = $response->ele_source_code;
                $plan_id = $response->plan_id;
                Log::info($gasPlanSourceCode);
                Log::info($elePlanSourceCode);
                Log::info($plan_id);


            }
        } catch (\Exception $e) {
            Log::info($e->getMessage(),[]);
            return  '';
        }

        $servicePlan = [];

        $gasService = ConnectionService::query()->where([['connection_application_id', '=', $this->connection->id],
            ['service_type','=', 'gas']])->first();
        $eleService = ConnectionService::query()->where([['connection_application_id', '=', $this->connection->id],
            ['service_type','=', 'power']])->first();


        if(!is_null($gasService)) {
            $servicePlan[] = [
                "fuel"=> "GAS",
                "planId" => $plan_id.'_G'.$state[0],
                "sourceCode"=> $gasPlanSourceCode
            ];
        }

        if($eleService) {
            $servicePlan[] = [
                "fuel"=> "ELE",
                "planId" => $plan_id.'_E'.$state[0],
                "sourceCode"=>  $elePlanSourceCode
            ];
        }

        return $servicePlan;

    }

    private function mapPlan($plan):string
    {
        return ConnectionService::ENERGY_PLAN_MAPPER[$plan];
    }

}
