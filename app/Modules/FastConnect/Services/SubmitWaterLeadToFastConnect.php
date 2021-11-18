<?php

namespace FastConnect\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

        // dd($this->productServiceData);
        // dd(json_encode($this->getData()));
        
        $authorization = 'Bearer ' . $this->accessToken;
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization
        ])
            ->withBody(json_encode($this->getData($this->application)), 'application/json')
            ->post(\config('fastconnect.root_url') . \config('fastconnect.submit_water_lead_url'));
        
        dd(json_decode($response->body(), true));
    }

    public function getData($lead)
    {
        return [
            "agent_code" => "3954V",
            "application_type" => "RESIDENTIAL",
            "locale" => "en",
            "address" => [
                "move_in_address" => [
                    // "unit_number" => $lead->unit_number,
                    "street_number" => $lead->street_number,
                    "street_name" => $lead->street_name,
                    "street_type" => $lead->getRoadType(),
                    "suburb" => $lead->city,
                    "state" => $lead->state,
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
                    // "title" => $lead->title,
                    "title" => 'MR',
                    "first_name" => $lead->first_name,
                    "middle_name" => $lead->middle_name,
                    "last_name" => $lead->last_name,
                    "date_of_birth" => $lead->dob,
                    "email" => $lead->email,
                    "phone_preference" => $lead->phone,
                    // "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => 2,
                            "number" => "EG123456",
                            "issuer_state_id" => 1,
                            "issuer_country_id" => 13,
                            "medicare_color" => "GREEN",
                            "medicare_irn" => 1,
                            "expiry" => "2023-01-01"
                        ]
                    ]
                ],
                "secondary" => [
                    "title" => "MR",
                    "first_name" => "Test",
                    "middle_name" => "Test",
                    "last_name" => "Test",
                    "date_of_birth" => "1990-10-18",
                    "email" => "email@example.com",
                    "phone_preference" => "0491570006",
                    "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => 2,
                            "number" => "EG123456",
                            "issuer_state_id" => 1,
                            "issuer_country_id" => 13,
                            "medicare_color" => "GREEN",
                            "medicare_irn" => 1,
                            "expiry" => "2023-01-01"
                        ]
                    ]
                ]
            ],

        ];
    }

    private function getMappedDate($date)
    {
        $oDate = new \DateTime($date);
        return $oDate->format("Y-m-d");
    }

    private function getMappedProperty($tenancy): string
    {
        return $tenancy ? FastConnectProductService::MAP_PROPERTY_TYPE[$tenancy] : '';
    }
}
