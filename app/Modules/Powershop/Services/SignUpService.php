<?php

namespace Powershop\Services;

use Exception;
use Carbon\Carbon;

use App\Models\APILog;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class SignUpService
{
    private array|Collection|ConnectionApplication|Model $application;
    private string $submitType;
    private int $elecKey;
    private int $gasKey;
    private int $utilityKeyCount = 0;

    const MAP_STATE = [
        "New South Wales" => 'NSW',
        "Victoria" => 'VIC',
        "Queensland" => 'QLD',
        "South Australia" => 'SA',
        "Northern Territory" => 'NT',
        "Tasmania" => 'TAS',
        "Australian Capital Territory" => 'ACT',
        "Western Australia" => 'WA',
    ];

    const MAP_PROPERTY_TYPE = [
        1 => 'residential',
        2 => 'business',
    ];

    const OPTION_SERVICE_TYPE = [
        'energy',
        'power',
        'gas'
    ];

    public function __construct(int $id = 0, $submitType = '')
    {
        if (!config('powershop.use_dummy_data')){
            $this->application = ConnectionApplication::findOrFail($id);
            
            if (!in_array($submitType, self::OPTION_SERVICE_TYPE))
                throw new Exception("Invalid submit service type");
            else
                $this->submitType = $submitType;
        }
        
    }

    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function sendCustomerData()
    {
        try {
            $url = config('powershop.base_url').config('powershop.send_customer_data_url');
            $url = APILog::setLoggerQuery($url, APILog::API_POWERSHOP_SEND_CUSTOMER_DATA, false);
            $data = config('powershop.use_dummy_data') ? $this->getDummyData() : $this->getCustomerData($this->submitType);
            info('Powershop send data', $data);
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'Authorization' => config('powershop.secret_token'),
            ])
            ->withBody(json_encode($data),'application-json')
            ->post($url);

            $response->throwIf(!$response->successful() && $response->status() != 422);

            $results = json_decode($response->body(), true);
            info('Powershop receive data', $results); 
            if (isset($results['data']['errors'])){
                $results = [
                    'status' => 'rejected',
                    'errors' => $this->getFormattedErrors($results['data']['errors']),
                ];
            }
            else {
                $results = [
                    'status' => 'in_progress',
                    'reference' => $results['data']['reference'],
                ];
            }
            return $results;
        } catch (\Illuminate\Http\Client\RequestException $exception){
            $statusCode = $exception->response->status();
            $responseJson = $exception->response->json();
            \Log::error('Powershop::SendCustomerData FAIL (see context)', [
                'status' => $statusCode,
                'message' => $responseJson,
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        } catch (Exception $exception) {
            \Log::error('Powershop::SendCustomerData FAIL (see context)', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }
    }

    private function getCustomerData($submitType): array
    {
        $data = [];

        $data['account_setup'] = [
            'account_type' => $this->getMappedProperty($this->application->property_type)
        ];
        $data['account_holder'] = [
            'title' => $this->application->title,
            'first_name' => $this->application->first_name,
            'last_name' => $this->application->last_name,
            'date_of_birth' => $this->getFormattedDate($this->application->dob),
            'phone_number' => $this->application->phone ?? $this->application->homephone,
        ];
        $data['login'] = [
            'email' => $this->application->email,
        ];
        $data['property_information'] = [
            'current_situation' => "moving",
            'supply_address' => [
                'flat_number' => $this->application->unit_number,
                'house_number' => $this->application->street_number,
                'street_name' => $this->application->street_name_only,
                'street_type' => $this->application->street_type,
                'postcode' => $this->application->postcode,
                'suburb' => $this->application->city,
                'region' => $this->getMappedState($this->application->state),
            ],
            'postal_address' => [
                'flat_number' => $this->application->billing_unit_number,
                'house_number' => $this->application->billing_street_number,
                'street_name' => $this->application->billing_street_name_only,
                'street_type' => $this->application->billing_street_type,
                'postcode' =>  $this->application->billing_postcode,
                'suburb' => $this->application->billing_city,
                'region' => $this->getMappedState($this->application->billing_state),
            ],
        ];
        if ($this->application->is_billing_same) {
            $data['property_information']['postal_address'] = $data['property_information']['supply_address'];
        }
        $hazData = $this->getHazards();
        if (!empty($hazData)) $data['property_information']['hazards'] = $hazData;
        $data['utility_details'] = $this->getUtilityDetails($submitType);
        $data['eligible_for_concessions'] = $this->getIsEligibleConcession();
        $data['payment_details'] = $this->getPaymentDetails();

        if ($this->application->authorizedPerson()->exists() && !empty($this->application->authorizedPerson->first_name)){
            $data['secondary_account_holders'] = [
                [
                    'title' =>  $this->application->authorizedPerson->title,
                    'first_name' => $this->application->authorizedPerson->first_name,
                    'last_name' => $this->application->authorizedPerson->last_name,
                    'date_of_birth' => $this->application->authorizedPerson->dob,
                    'phone_number' => $this->application->authorizedPerson->phone,
                ]
            ];
        }

        $vulData = $this->getVulnerabilities();
        if (!empty($vulData)) $data['vulnerabilities'] = $vulData;

        $data['terms_and_conditions_accepted_at'] = $this->getFormattedDate(Carbon::now()->format('Y-m-d H:i:s')); // TODO: get timestamp
        return $data;
    }


    private function getMappedProperty($property): string
    {
        return $property ? self::MAP_PROPERTY_TYPE[$property] : '';
    }

    private function getMappedState($state): string
    {
        return $state ? self::MAP_STATE[$state] : '';
    }

    private function getElecDetails()
    {
        $service = ConnectionService::where('connection_application_id', $this->application->id)
                    ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
                    ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
                    ->first();
        
        if (!$service) {
            throw new Exception("Electricity connection service for application not found");
        }

        $data = [
            "utility_type" => 'electricity',
            "connection_number" => $this->application->nmi,
            "proposed_start_date" => $this->getFormattedDate($service->connection_date ?? $this->application->moving_date),
            "is_connection_currently_active" => false,
            "estimated_billing" => [
                'cost' => $this->application->powershopPaymentInfo->estimated_elec_billing_cost,
                'period' => 'quarterly',
            ],
        ];

        $promoCode = PromotionCodeService::getCodeviaAPI($service->service_type, $service->plan_type, $this->application->state, $this->application->postcode, $this->application->nmi);

        if (!empty($promoCode))
            $data['promotion'] = [
                "promotion_code" => $promoCode,
                "promotion_terms_and_conditions_accepted_at" => $this->getFormattedDate(Carbon::now()->format('Y-m-d H:i:s')),
            ];

        if (!empty($this->application->additional_access_information))
            $data['meter_details']['meter_location_notes'] = $this->application->additional_access_information;

        $this->elecKey = $this->utilityKeyCount;
        $this->utilityKeyCount += 1;
        return $data;
    }

    private function getGasDetails()
    {
        $service = ConnectionService::where('connection_application_id', $this->application->id)
                    ->where('service_type', ConnectionService::TYPE_GAS)
                    ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
                    ->first();
        
        if (!$service) {
            throw new Exception("Gas connection service for application not found");
        }

        $data = [
            "utility_type" => 'gas',
            "connection_number" => $this->application->mirn_checksum,
            "proposed_start_date" => $this->getFormattedDate($service->connection_date ?? $this->application->moving_date),
            "is_connection_currently_active" => false,
            "estimated_billing" => [
                'cost' => $this->application->powershopPaymentInfo->estimated_gas_billing_cost,
                'period' => 'quarterly',
            ],
        ];

        $promoCode = PromotionCodeService::getCodeviaAPI($service->service_type, $service->plan_type, $this->application->state, $this->application->postcode);

        if (!empty($promoCode))
            $data['promotion'] = [
                "promotion_code" => $promoCode,
                "promotion_terms_and_conditions_accepted_at" => $this->getFormattedDate(Carbon::now()->format('Y-m-d H:i:s')),
            ];

        $this->gasKey = $this->utilityKeyCount;
        $this->utilityKeyCount += 1;

        return $data;
    }

    private function getUtilityDetails($submitType)
    {
        $data = [];
        
        if ($submitType == 'energy' || $submitType == 'power') {
            $data[] = $this->getElecDetails();
        }
        
        if ($submitType == 'energy' || $submitType == 'gas') {
            $data[] = $this->getGasDetails();
        }

        return $data;
    }

    private function getVulnerabilities()
    {
        $data = [];
        
        if ($this->application->is_gas_life_support || $this->application->is_power_life_support) {
            $data['dependency_type'] = 'Life support';
            $data['medical_details_disclaimer_accepted_at'] = $this->getFormattedDate(Carbon::parse($this->application->life_support_accepted_at)->format('Y-m-d H:i:s')); // TODO: get timestamp

            if ($this->application->is_gas_life_support && $this->application->is_power_life_support)
            {
                $data['medical_reason'] = 'Life support electricity and gas';
            }
            else if ($this->application->is_gas_life_support)
            {
                $data['medical_reason'] = 'Life support gas';
            }
            else {
                $data['medical_reason'] = 'Life support electricity';
            }
        }

        return $data;
    }

    private function getIsEligibleConcession()
    {
        return !empty($this->application->concession_card_type);
    }

    private function getHazards()
    {
        $data = [];
        
        if ($this->application->is_any_unrestrained_animal)
            $data['dog'] = true;
        if ($this->application->is_renovation_on)
            $data['electrical_safety_issue'] = true;

        return $data;
    }

    private function getPaymentDetails()
    {
        $paymentInfo = $this->application->powershopPaymentInfo;
        if(!$paymentInfo || empty($paymentInfo->px_dps_billing_id))
            throw new \Exception('Powershop payment info missing or no token/billing id');
        return [
            "card" => [
                'card_type' => strtolower($paymentInfo->px_card_type),
                'masked_card_number' => $paymentInfo->px_card_number,
                'expiry_date' => $paymentInfo->px_card_expire_date,
                'cardholder_name' => $paymentInfo->px_card_holder_name,
                'token' => $paymentInfo->px_dps_billing_id,
                'terms_and_conditions_accepted_at' => $this->getFormattedDate($paymentInfo->verified_at),
                'preferred' => true,
            ]
        ];

        // // DUMMY
        // return [
        //     "card" => [
        //         "card_type" => "mastercard",
        //         "masked_card_number" => "xxxxxxxxxxxxx74",
        //         "expiry_date" => "122022",
        //         "cardholder_name" => "Cristiano Ronaldo",
        //         "token" => "281174b44b44b397b38a",
        //         "terms_and_conditions_accepted_at" => Carbon::now()->format('Y-m-d'),
        //         "preferred" => true,
        //     ],
        // ];
    }

    private function getFormattedDate(string $date)
    {
        return Carbon::parse($date)->setTimezone('Australia/Victoria')->format('Y-m-d'); //TODO
    }

    private function getDummyData()
    {
        $email = 'cronaldo2@mailinator.com';
        $elecDate = '2022-08-23';
        $gasDate = '2022-08-23';
        return [
            "account_setup" => ["account_type" => "residential"],
            "account_holder" => [
                "title" => "Mr",
                "first_name" => "Cristiano",
                "last_name" => "Ronaldo",
                "date_of_birth" => "1985-02-05",
                "phone_number" => "0429488395",
            ],
            "login" => ["email" => $email],
            "secondary_account_holders" => [
                [
                    "title" => "Ms",
                    "first_name" => "Georgina",
                    "last_name" => "Rodriguez",
                    "date_of_birth" => "1994-01-27",
                    "phone_number" => "0402922123",
                ],
            ],
            "property_information" => [
                "current_situation" => "moving",
                "hazards" => ["dog" => true, "electrical_safety_issue" => true],
                "supply_address" => [
                    "house_number_suffix" => "",
                    "flat_number" => "",
                    "house_number" => "50",
                    "street_name" => "DAMIEN",
                    "street_type" => "ST",
                    "street_suffix" => "",
                    "postcode" => "3224",
                    "suburb" => "LEOPOLD",
                    "region" => "VIC",
                ],
                "postal_address" => [
                    "flat_number" => "",
                    "flat_type" => "",
                    "floor_number" => "",
                    "floor_type" => "",
                    "house_number" => "9",
                    "house_number_suffix" => "",
                    "street_name" => "BEATTY",
                    "street_type" => "ST",
                    "postcode" => "4305",
                    "suburb" => "COALFALLS",
                    "region" => "VIC",
                ],
            ],
            "utility_details" => [
                [
                    "utility_type" => "electricity",
                    "connection_number" => "62037972735",
                    "estimated_billing" => ["cost" => 366, "period" => "quarterly"],
                    "proposed_start_date" => $elecDate,
                    "is_connection_currently_active" => false,
                    "meter_details" => ["meter_location_notes" => "Customer on site"],
                    "promotion" => [
                        "promotion_code" => "HoodPS100%CarbonNeutral",
                        "promotion_terms_and_conditions_accepted_at" => Carbon::now()->format('Y-m-d'),
                    ],
                ],
                [
                    "utility_type" => "gas",
                    "connection_number" => "53307295967",
                    "estimated_billing" => ["cost" => "50", "period" => "quarterly"],
                    "proposed_start_date" => $gasDate,
                    "is_connection_currently_active" => false,
                    "promotion" => [
                        "promotion_code" => "HoodPS100%CarbonNeutral",
                        "promotion_terms_and_conditions_accepted_at" => Carbon::now()->format('Y-m-d'),
                    ],
                ],
            ],
            "vulnerabilities" => [
                "dependency_type" => "Life Support",
                "medical_reason" => "Life support electricity and gas",
                "medical_details_disclaimer_accepted_at" => Carbon::now()->format('Y-m-d'),
            ],
            "payment_details" => [
                "card" => [
                    "card_type" => "mastercard",
                    "masked_card_number" => "xxxxxxxxxxxxx74",
                    "expiry_date" => "122022",
                    "cardholder_name" => "Cristiano Ronaldo",
                    "token" => "281174b44b44b397b38a",
                    "terms_and_conditions_accepted_at" => Carbon::now()->format('Y-m-d'),
                    "preferred" => true,
                ],
            ],
            "eligible_for_concessions" => true,
            "terms_and_conditions_accepted_at" => Carbon::now()->format('Y-m-d'),
        ];

    }

    private function getFormattedErrors ($errors) {
        $data = [];
        foreach ($errors as $errKey => $errVal) {
            if ($errKey == 'utility_details') {
                foreach ($errVal as $subErrKey => $subErrVal){
                    if(isset($this->elecKey) && $this->elecKey == $subErrKey){
                        $powerData = [];
                        foreach($subErrVal as $powerKey => $powerVal){
                            self::recursiveStore($powerVal, $powerData, $powerKey);
                        }
                        $data[ConnectionService::TYPE_ELECTRICITY] = $powerData;  
                    }    
                    if (isset($this->gasKey) && $this->gasKey == $subErrKey) {
                        $gasData = [];
                        foreach($subErrVal as $gasKey => $gasVal){
                            self::recursiveStore($gasVal, $gasData, $gasKey);
                        }
                        
                        $data[ConnectionService::TYPE_GAS] = $gasData;  
                    }
                }
            }
            else {
                $keyData = [];
                foreach ($errVal as $subErrKey => $subErrVal){
                    self::recursiveStore($subErrVal, $keyData, $subErrKey);
                }
                $data[$errKey] = $keyData;
            }
        }

        return $data;
    }

    private function recursiveStore(array $array, &$data, $preText = ''){
        foreach($array as $k => $v){
            if (!is_int($k)){
                $preText = $preText . ' ' . $k;
            }
            if (!is_array($v)) {
                $data[] = $preText . ' '. $v;
            }
            else {
                self::recursiveStore($v, $data, $preText);
            }
        }
    }

}
