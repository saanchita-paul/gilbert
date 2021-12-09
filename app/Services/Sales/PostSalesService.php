<?php


namespace App\Services\Sales;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Mutation;
use GraphQL\Variable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostSalesService
{
    use SalesResponseHandle;

    private $connection;
    private $identification;
    private $accessToken;
    private $chatbotUri;
    private $planType;
    public function __construct(int $id)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->connection = ConnectionApplication::with('connectionServices')->where('id', $id)->firstOrFail();
        $this->identification = $this->connection->identification;
        $this->accessToken = (new GetAccessToken())->getAccessToken();
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
        $premiseRelationship= $this->connection->tenancy_type == 1 ? "TENANT":"OWNER";
        $customer = [
            'title'=> strtoupper($this->connection->title),
            'firstName'=> $this->connection->first_name,
            'lastName'=> $this->connection->last_name,
            'emailAddress'=> $this->connection->email,
            'dateOfBirth'=>  $this->connection->dob,
            'premiseRelationship'=> $premiseRelationship,
            'phone'=> [
                [
                    'type'=> $this->connection->phone_type == 1 ? 'MOBILE': 'HOME',
                    'number'=>$this->connection->phone_type == 1 ? $this->connection->phone:  $this->connection->homephone
                ]
            ],
            'preferredContactMethod' => 'EMAIL',
            'identification'=> $this->getIdentification()
        ];
        $streetType = self::getStreetType($this->connection->street_name);

        $energisation = [
            'connectionDate'=>(new Carbon($this->connection->moving_date))->format('Y-m-d'),
            'renovationsSinceDeenergisation'=> false,
            'renovationsInProgressOrPlanned'=> false,
            'afterHoursServiceOrder' => $this->getAfterHoursServiceOrder($this->connection->moving_date),
        ];


        $premise = [
            'mirn'=> $this->connection->mirn,
            'nmi'=> $this->connection->nmi,

            'address'=> [
                'unitNumber'=> $this->connection->unit_number,
                'streetNumber'=> $this->connection->street_number,
                'streetName'=> $this->connection->street_address,
                'streetType'=> $streetType,
                'suburb'=> $this->connection->city,
                'state'=> $this->stateMap($this->connection->state),
                'postcode'=> $this->connection->postcode,
            ],
            'solarDetails'=> [
                'solarPower'=> $this->connection->has_solar == 1 ?true:false,
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
            "energisation"=> $energisation,
            "premise"=> $premise,
            "offers"=> $offers,
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


                Log::info('End Sale API request body');
                Log::info($variables);
                Log::info('Start Sale API request body');
            $results = $client->runQuery($gql, false, $variables );
            return $this->processEaData($results->getResponseBody());

        } catch (\Exception $e)
        {
            throw new \Exception("EA Sales API ERROR: " . $e->getMessage());
        }


    }

    private function getAfterHoursServiceOrder($date): bool
    {
        $tz = 11;
        $maxTime =today($tz)->addHours(11)->addMinutes(30);
        return Carbon::parse($date, $tz)->isCurrentDay() && now($tz)->greaterThan($maxTime);
    }

    /**
     * @throws \Exception
     */
    public function processEaData($results)
    {
        Log::info('End Sale API Response');
        Log::info(json_encode($results));
        Log::info('Start Sale API Response');

        $data = json_decode($results);
        $submitSallData = $data?->data?->submitSale;
        $quotes = $submitSallData?->quotes;
        $salesId = $submitSallData?->id;

        $this->handleResponse($quotes, $this->connection->id, ['lead_reference' => $salesId]);
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

    private function prepareOffer()
    {

//        Log::info('show Prepare call is called');
        $plan = $this->getPlanType();
        $state = $this->stateMap( $this->connection->state);
        $gasPlanSourceCode = '';
        $elePlanSourceCode = '';
        $plan_id = '';

        try {
            $response = Http::post($this->chatbotUri.'/api/get-plan-details',['plan' => $plan,'state' => $state, 'postcode' => $this->connection->postcode]);

            if($response->status() == 200) {
                $response = json_decode($response->body());
                $gasPlanSourceCode = $response->gas_source_code;
                $elePlanSourceCode = $response->ele_source_code;
                $plan_id = $response->plan_id;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage(),[]);
            return  '';
        }

        $servicePlan = [];
        $gasService = ConnectionService::query()
            ->where('connection_application_id',  $this->connection->id )
            ->where('service_type', ConnectionService::TYPE_GAS)
            ->whereNull('lead_reference')
            ->first();

        $eleService = ConnectionService::query()
            ->where('connection_application_id',  $this->connection->id )
            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
            ->whereNull('lead_reference')
            ->first();


        if(!is_null($gasService)) {
            $servicePlan[] = [
                "fuel"=> "GAS",
                "planId" => $plan_id.'-G'.$state[0],
                "sourceCode"=> $gasPlanSourceCode
            ];
        }

        if($eleService) {
            $servicePlan[] = [
                "fuel"=> "ELE",
                "planId" => $plan_id.'-E'.$state[0],
                "sourceCode"=>  $elePlanSourceCode
            ];
        }

        return $servicePlan;

    }

}
