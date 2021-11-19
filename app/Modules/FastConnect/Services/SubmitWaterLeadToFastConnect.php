<?php

namespace FastConnect\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Identification;
use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use FastConnect\Services\FastConnectProductService;

class SubmitWaterLeadToFastConnect
{
    private string $accessToken;
    private int $applicationId;
    private array|Collection|ConnectionApplication|Model $application;
    private $productServiceData;

    const MAP_PROPERTY_TYPE = [
        1 => 'rented',
        2 => 'owning '
    ];

    const MAP_TITLE = [
        'Mr' => 'MR',
        'Mrs' => 'MRS',
        'Ms' => 'MS'
    ];

    const MAP_IDENTIFICATION_PROFILE_ID = [
        Identification::TYPE_DRIVING_LICENCE => 2,
        Identification::TYPE_MEDICARE => 1,
        Identification::TYPE_PASSPORT => 3
    ];

    const MAP_IDENTIFICATION_STATE = [
        "New South Wales" => 2,
        "Victoria" => 7,
        "Queensland" => 4,
        "South Australia" => 5,
        "Northern Territory" => 3,
        "Tasmania" => 6,
        "Australian Capital Territory" => 1,
    ];

    const MAP_IDENTIFICATION_COUNTRY = [
        "AUS" => 13,
        "Australia" => 13
    ];

    const MAP_STATE = [
        "New South Wales" => 'NSW',
        "Victoria" => 'VIC',
        "Queensland" => 'QLD',
        "South Australia" => 'SA',
        "Northern Territory" => 'NT',
        "Tasmania" => 'TAS',
        "Australian Capital Territory" => 'ACT',
    ];

    public function __construct(int $id) {
        $this->applicationId = $id;
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices', 'authorizedPerson']);
        $this->authenticate();
    }

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])
            ->post( \config('fastconnect.root_url') . \config('fastconnect.get_water_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }

    public function submitWaterLead()
    {
        $productService = new FastConnectProductService($this->applicationId);
        $this->productServiceData = $productService->getProductGroup()->getProductDetails();

        $mappedData = $this->getData($this->application);
        $mappedAllData = $this-> addExtraData($mappedData);

        // dd($this->productServiceData);
        // dd($mappedAllData);
        
        $authorization = 'Bearer ' . $this->accessToken;
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization
        ])
            ->withBody(json_encode($mappedAllData), 'application/json')
            ->post(\config('fastconnect.root_url') . \config('fastconnect.submit_water_lead_url'));
        
        dd(($response));
    }

    public function getData($lead)
    {
        return [
            "agent_code" => "3954V",
            "application_type" => "RESIDENTIAL",
            "locale" => "en",
            "address" => [
                "move_in_address" => [
                    "street_number" => $lead->street_number,
                    "street_name" => $lead->street_name,
                    "street_type" => $lead->getRoadType(),
                    "suburb" => $lead->city,
                    "state" => $this->getMappedState($lead->state),
                    "post_code" => $lead->postcode,
                    // "property_type" => "old",
                    "property_ownership" => $this->getMappedProperty($this->application->tenancy_type),
                    // "landlord_name" => "string",
                    // "landlord_suburb" => "string",
                    // "landlord_phone" => "string"
                ]
            ],
            "products" => [
                [
                    "id" => $this->productServiceData->productDetails[0]['sub_groups'][0]['products'][0]['id'],
                    "product_group_id" => $this->productServiceData->waterConnection->id,
                    "contract_id" => $this->productServiceData->productDetails[0]['sub_groups'][0]['products'][0]['contracts'][0]['id'],
                    "requested_date" => $this->getMappedDate($lead->moving_date),
                    "marketing" => false
                ]
            ],
            "contact" => [
                "primary" => [
                    "title" => $this->getMappedTitle($lead->title),
                    "first_name" => $lead->first_name,
                    "middle_name" => $lead->middle_name,
                    "last_name" => $lead->last_name,
                    "date_of_birth" => $lead->dob,
                    "email" => $lead->email,
                    "phone_preference" => $lead->phone,
                    // "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => $this->getMappedIdentificationType($lead->identification->type),
                            "number" => $lead->identification->card_number,
                            "expiry" =>  $lead->identification->expire_date,
                        ]
                    ]
                ],
                // "secondary" => [
                //     "title" => "MR",
                //     "first_name" => "Test",
                //     "middle_name" => "Test",
                //     "last_name" => "Test",
                //     "date_of_birth" => "1990-10-18",
                //     "email" => "email@example.com",
                //     "phone_preference" => "0491570006",
                //     "phone_alternate" => "0491570006",
                //     "identification" => [
                //         [
                //             "identification_profile_item_id" => 2,
                //             "number" => "EG123456",
                //             "issuer_state_id" => 1,
                //             "issuer_country_id" => 13,
                //             "medicare_color" => "GREEN",
                //             "medicare_irn" => 1,
                //             "expiry" => "2023-01-01"
                //         ]
                //     ]
                // ]
            ],
            "billing_location" => [
                "connection" => [
                  "location_type" => "ADDRESS",
                  "address" => [
                    "unit_number" => "1",
                    "lot_number" => "1",
                    "street_number" => "10",
                    "street_name" => "Main",
                    "street_type" => "St",
                    "suburb" => "Melbourne",
                    "state" => "VIC",
                    "post_code" => "3000",
                    "care_of" => "Bill Smith"
                  ]
                ]
            ]

        ];
    }

    private function getMappedDate($date)
    {
        $oDate = new \DateTime($date);
        return $oDate->format("Y-m-d");
    }

    private function getMappedProperty($tenancy): string
    {
        return $tenancy ? SubmitWaterLeadToFastConnect::MAP_PROPERTY_TYPE[$tenancy] : '';
    }

    private function getMappedTitle($title): string
    {
        return $title ? SubmitWaterLeadToFastConnect::MAP_TITLE[$title] : $title;
    }

    private function getMappedState($state): string
    {
        return $state ? SubmitWaterLeadToFastConnect::MAP_STATE[$state] : $state;
    }

    private function getMappedIdentificationType($type): int
    {
        return SubmitWaterLeadToFastConnect::MAP_IDENTIFICATION_PROFILE_ID[$type];
    }

    private function getMappedIdentificationState($state)
    {
        return SubmitWaterLeadToFastConnect::MAP_IDENTIFICATION_STATE[$state];
    }

    private function getMappedIdentificationCountry($country)
    {
        return SubmitWaterLeadToFastConnect::MAP_IDENTIFICATION_COUNTRY[strtoupper($country)];
    }

    private function addExtraData($data)
    {
        !is_null($this->application->unit_number) ? $data['address']['move_in_address']['unit_number'] = $this->application->unit_number : null;
        switch ($this->application->identification->type) {
            case 1: //TYPE_PASSPORT
              $data['contact']['primary']['identification'][0]['issuer_country_id'] =
                $this->getMappedIdentificationCountry($this->application->identification->country);
              break;
            case 2: //TYPE_DRIVING_LICENCE
              $data['contact']['primary']['identification'][0]['issuer_state_id'] =
                $this->getMappedIdentificationState($this->application->identification->state);
              break;
            case 3: //TYPE_MEDICARE
              $data['contact']['primary']['identification'][0]['medicare_color'] =
                $this->application->identification->card_color;
              $data['contact']['primary']['identification'][0]['medicare_irn'] =
                (int)$this->application->identification->special_number;
              break;
          }
        return $data;
    }
}
