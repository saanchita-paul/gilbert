<?php

namespace FastConnect\Services;

use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastConnectProductService
{
    private string $accessToken;
    private array|Collection|ConnectionApplication|Model $application;
    private array $productConnectionGroups;
    public $waterConnection;
    public array $productDetails;

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
        1 => 'rented',
        2 => 'owning'
    ];

    public function __construct(int $id) {
        $this->application = ConnectionApplication::findOrFail($id);
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

    public function getProductGroup()
    {
        $authorization = 'Bearer ' . $this->accessToken;
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization
        ])
            ->withBody(json_encode($this->getProductGroupData()), 'application/json')
            ->post(\config('fastconnect.root_url') . \config('fastconnect.get_product_group_uri'));
        info('Water Log',$this->getProductGroupData());
        info('Water Log',json_decode($response->body(), true));


        $this->productConnectionGroups = json_decode($response->body())->connection_groups;
        return $this;
    }

    public function getProductDetails()
    {
        $waterConnectionIndex = collect($this->productConnectionGroups)->search(function ($item) {
            return $item->name == 'Water';
        });
        $this->waterConnection = $this->productConnectionGroups[$waterConnectionIndex];

        $url = config('fastconnect.root_url') . config('fastconnect.get_product_details_uri');
        $authorization = 'Bearer ' . $this->accessToken;
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization
        ])
            ->withBody(json_encode($this->getProductDetailsData()), 'application/json')
            ->post($url);

        $this->productDetails = json_decode($response->body(), true);
        info("FC PRODUCT API", [
            'url' => $url,
            'request_body' => $this->getProductDetailsData(),
            'response_body' => $this->productDetails,
            'response_status' => $response->status()
        ]);
        return $this;
    }

    private function getProductGroupData()
    {
        return [
            "agent_code" => "3954V",
            "move_in_address" => [
                "suburb" => $this->application->city,
                "post_code" => $this->application->postcode,
                "state" => $this->getMappedState($this->application->state),
                "property_ownership" => $this->getMappedProperty($this->application->tenancy_type),
                // "property_type" => "old",
                "street_name" => $this->application->street_name,
                "street_number" => $this->application->street_number,
//                 "street_type" => "St"
            ]
        ];
    }

    private function getProductDetailsData()
    {
        return [
            "address" => [
                "move_in_address" => [
                    "suburb" => $this->application->city,
                    "post_code" => $this->application->postcode,
                    "state" => $this->getMappedState($this->application->state),
                    "property_ownership" => $this->getMappedProperty($this->application->tenancy_type),
                    // "property_type" => "old",
                    "street_name" => $this->application->street_name,
                    "street_number" => $this->application->street_number,
                    // "street_type" => "St"
                ],
                // "move_out_address" => [
                //     "suburb" => "Melbourne",
                //     "post_code" => "3000",
                //     "state" => "VIC",
                //     "property_ownership" => "rented",
                //     "property_type" => "old",
                //     "street_name" => "Main",
                //     "street_number" => "10",
                //     "street_type" => "St"
                // ]
            ],
            'agent_code' => "3954V",
            "selected_groups" => [
                [
                    "connection_type" => $this->waterConnection->connection_type,
                    "id" => $this->waterConnection->id
                ]
            ],
            "selected_products" => [

            ]
        ];
    }

    private function getMappedState($state): string
    {
        return $state ? FastConnectProductService::MAP_STATE[$state] : '';
    }

    private function getMappedProperty($tenancy): string
    {
        return $tenancy ? FastConnectProductService::MAP_PROPERTY_TYPE[$tenancy] : '';
    }
}
