<?php


namespace App\Services\Sales;


use App\Models\APILog;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\Office;
use App\Services\Logger\LogSalesService;
use Carbon\Carbon;
use GraphQL\Client;
use GraphQL\Mutation;
use GraphQL\Variable;
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
    private $tz = 11;
    public function __construct(int $id)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->connection = ConnectionApplication::with('connectionServices')->where('id', $id)->firstOrFail();
        $this->identification = $this->connection->identification;
        $this->accessToken = (new GetAccessToken())->getAccessToken($id);
        $this->tz = config('ea.au_time_zone', 11);

    }

    public function getPlanType($submitType)
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        $connectionService = ConnectionService::where('connection_application_id', $this->connection->id)
            ->whereIn('service_type', $services)
            ->where('provider_name', '=', 'ea')
            ->first();

        if($connectionService) {
            return $connectionService->plan_type;
        }
        return throw new \Exception('[PostSalesService:getPlanType] plan type not found');
    }

    public function postToEa($submitType, $applicationId)
    {

        $vendorCode= $this->getVendorCode();
        $id = $vendorCode . $this->connection->id . time ();

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

        $energisation = [
            'connectionDate'=>(new Carbon($this->connection->moving_date))->format('Y-m-d'),
            'renovationsSinceDeenergisation'=> false,
            'renovationsInProgressOrPlanned'=> false,
            'afterHoursServiceOrder' => $this->getAfterHoursServiceOrder(),
        ];


        $premise = [
            'mirn'=> $this->connection->mirn,
            'nmi'=> $this->connection->nmi,

            'address'=> [
                'unitNumber'=> $this->connection->unit_number,
                'streetNumber'=> $this->connection->street_number,
                // 'streetName'=> $this->connection->street_address,
                'streetName'=> $this->connection->street_name_only,
                'streetType'=> $this->connection->street_type,
                'suburb'=> $this->connection->city,
                'state'=> $this->stateMap($this->connection->state),
                'postcode'=> $this->connection->postcode,
            ],
            'solarDetails'=> [
                'solarPower'=> $this->connection->has_solar == 1 ?true:false,
            ]
        ];

        $offers = $this->prepareOffer($submitType);
        $mailingAddressType = 'STREET';

        $streetMailingAddress = [
            'unitNumber'=> $this->connection->unit_number,
            'streetNumber'=> $this->connection->street_number,
            // 'streetName'=> $this->connection->street_address,
            'streetName'=> $this->connection->street_name_only,
            'streetType'=> $this->connection->street_type,
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
            "carbonNeutralOptIn" => $this->connection->ea_go_neutral === 1 ? true : false,
        ];

        $variables= [
            'data'=>$eaData
        ];

        $logSalesService = new LogSalesService();
        $loggerResponse = null;


        try{
            $url = env('EA_SALES_URL','https://apigw-nonprod.energyaustralia.com.au/graphql');
            $XEAEnv = config('ea.x_ea_env');
            $header = ['Authorization' => $this->accessToken, 'X-EA-Env' => $XEAEnv];
            $client = new Client($url, $header);
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
                // creating new log for sales api
            $loggerResponse = $logSalesService->createSalesLog(
                $url,
                APILog::API_SALES_API_SUBMIT,
                'POST',
                json_encode($variables),
                json_encode($header)
            );

            $results = $client->runQuery($gql, false, $variables );

            //update sales log with response

            $logSalesService->updateSalesLog($loggerResponse->key,
                json_encode($results->getData()),
                json_encode([]),
                200
            );

            return $this->processEaData($results->getResponseBody());

        } catch (\Exception $e)
        {
            // ConnectionApplication::where('id', $applicationId)->update([
            //     'is_running_submission' => 0,
            // ]);

            $logSalesService->updateSalesLog($loggerResponse->key,
                json_encode($e->getMessage()),
                json_encode([]),
                400
            );
            // throw new \Exception("EA Sales API ERROR: " . $e->getMessage());
            throw $e;
        }


    }

    private function logFlagDetails(?bool $flag)
    {
        if (app()->environment('production')) {
            return;
        }
        $eaService = ConnectionService::query()->where('connection_application_id', $this->connection->id)
            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
            ->first();
        $distributor = null;
        if(!empty($eaService))
        {
            $distributor = $eaService->distributor;
        }

        $state = $this->stateMap( $this->connection->state);
        Log::info('After Hour Flags ', [
            'state'=> $state,
            'distributor'=> $distributor,
            'connection_date'=> $this->connection->moving_date,
            'after_hour_flag'=> $flag,
        ]);
    }

    private function getAfterHoursServiceOrder(): bool
    {
        $afterHourFlag = $this->connection->getAfterHourPayee();
        $this->connection->update(['after_hour_flag' => $afterHourFlag]);

        $this->logFlagDetails($afterHourFlag);

        return $afterHourFlag;


//        $eaService = ConnectionService::query()->where('connection_application_id', $this->connection->id)
//            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
//            ->first();
//        $distributor = null;
//        if(!empty($eaService))
//        {
//            $distributor = $eaService->distributor;
//        }
//
//        $state = $this->stateMap( $this->connection->state);
//
//        $afterHourFlag = false;
//
//        if($this->isSameDayConnection()) {
//            $afterHourFlag = $this->handleSameDayConnection($distributor, $state);
//        }
//
//        if($this->isNextDayConnection()) {
//            $afterHourFlag = $this->handleNextDayConnection($distributor, $state);
//        }
//        $this->connection->update(['after_hour_flag' => $afterHourFlag]);
//
//        Log::info('After Hour Flags ', [
//            'state'=> $state,
//            'distributor'=> $distributor,
//            'connection_date'=> $this->connection->moving_date,
//            'after_hour_flag'=> $afterHourFlag,
//        ]);
//
//        return $afterHourFlag;

    }


    private function isSameDayConnection():bool
    {
        $date = $this->connection->moving_date;
        return Carbon::parse($date, $this->tz)->isCurrentDay();
    }

    private function isNextDayConnection():bool
    {
        $date = $this->connection->moving_date;
        $tomorrow = today($this->tz)->addDay(1);
        return (Carbon::parse($date, $this->tz))->toDateString() === $tomorrow->toDateString();
    }

    private function handleSameDayConnection($distributor, $state): bool
    {
        $maxTime = today($this->tz)->addHours(11)->addMinutes(30);
        $currentTime = Carbon::now()->timezone($this->tz);
        if($currentTime->lte($maxTime) && (($state === 'NSW' && $distributor === 'Ausgrid')
            || ($state === 'VIC' && $distributor === 'AusNet Services')
            || ($state === 'SA' && $distributor === 'ETSA'))
        ) {
            return false;
        }

        return true;

    }


    private function handleNextDayConnection($distributor, $state)
    {
        $maxTime = today($this->tz)->addHours(11)->addMinutes(30);
        $currentTime = Carbon::now()->timezone($this->tz);

        if($currentTime->lte($maxTime)) {
            return false;
        }
        return true;
    }


    /**
     * @throws \Exception
     */
    public function processEaData($results)
    {
        Log::info('Sale API Response');
        Log::info($results);

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

    private function getVendorCode(): string
    {
        /** @var Office $office */
        $office = $this->connection->office;
        return $office->getVendorCode();
    }

    private function stateMap($state)
    {

        $stateList = [
            'New South Wales' => 'NSW',
            'Victoria' => 'VIC',
            'Queensland' => 'QLD',
            'South Australia' => 'SA',
            'Northern Territory' => 'NT',
            'Tasmania' => 'TAS',
            'Western Australia' => 'WA',
            'Australian Capital Territory' => 'ACT'];
        if (array_key_exists($state, $stateList)) {
            return $stateList[$state];
        }
        return $state;

    }

    private static function getStreetType(string $streetAddress): string
    {
        $data = explode(' ', $streetAddress);
        return $data[sizeof($data) - 1];
    }

    /**
     * @throws \Exception
     */
    private function prepareOffer($submitType)
    {
        $plan = $this->getPlanType($submitType);
        $state = $this->stateMap( $this->connection->state);
        $gasPlanSourceCode = '';
        $elePlanSourceCode = '';
        $plan_id = '';

        try {
            $url = $this->chatbotUri.'/api/get-plan-details';
            $response = Http::post($url, ['plan' => $plan,'state' => $state, 'postcode' => $this->connection->postcode]);

            if($response->status() == 200) {
                $response = json_decode($response->body());
                $gasPlanSourceCode = $response->gas_source_code;
                $elePlanSourceCode = $response->ele_source_code;
                $plan_id = $response->plan_id;
            }
        } catch (\Exception $e) {
            throw new \Exception("[PostSalesService:prepareOffer]: Error from chatbot source-code API: ". $e->getMessage());
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


        if(!is_null($gasService) && ($submitType === 'energy' || $submitType === 'gas')) {
            $servicePlan[] = [
                "fuel"=> "GAS",
                "planId" => $plan_id.'-G'.$state[0],
                "sourceCode"=> $gasPlanSourceCode
            ];
        }

        if($eleService && ($submitType === 'energy' || $submitType === 'power')) {
            $servicePlan[] = [
                "fuel"=> "ELE",
                "planId" => $plan_id.'-E'.$state[0],
                "sourceCode"=>  $elePlanSourceCode
            ];
        }
        return $servicePlan;

    }

}
