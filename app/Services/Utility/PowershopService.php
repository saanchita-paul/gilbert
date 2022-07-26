<?php

namespace App\Services\Utility;

use Exception;
use Carbon\Carbon;
use App\Models\APILog;
use App\Models\Identification;
use function PHPSTORM_META\map;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;

use Illuminate\Support\Str;


class PowershopService
{
    private array|Collection|ConnectionApplication|Model $application;

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

    const MAP_ELIGIBLE_FOR_CONCESSION = [
        1 => true,
        0 => false,
    ];

    const MAP_SERVICE = [
        'gas' => 'Gas',
        'power' => 'Electricity',
    ];

    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function sendCustomerData(int $id)
    {
        try {
            $this->application = ConnectionApplication::findOrFail($id);
//             dd($this->getCustomerData());
            $url = config('powershop.base_url').config('powershop.send_customer_data_url');

            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'Authorization' => 'Token token=bd3ebe302f742553eef05f496ad6946a'
            ])
                ->withBody(json_encode($this->getCustomerData()),'application-json')
                ->post($url);
//            dd($response->body());
            return json_decode($response->body(), true);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function getCustomerData(): array
    {
        return [
            "account_setup" => [
                'account_type' => $this->getMappedProperty($this->application->property_type),
            ],
            "account_holder" => [
                'title' => $this->application->title,
                'first_name' => $this->application->first_name,
                'last_name' => $this->application->last_name,
                'date_of_birth' => $this->application->dob,
                'phone_number' => $this->application->phone,
            ],
            "login" => [
                'email' => $this->application->email,
            ],
            "secondary_account_holders" => [
                [
                    'title' =>  $this->application->authorizedPerson->title,
                    'first_name' => $this->application->authorizedPerson->first_name ? $this->application->authorizedPerson->first_name : "N/A",
                    'last_name' => $this->application->authorizedPerson->last_name,
                    'date_of_birth' => $this->application->authorizedPerson->dob,
                    'phone_number' => $this->application->authorizedPerson->phone,
                ]
            ],
            'property_information' => [
                'current_situation' => "existing",
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
                    'flat_number' => $this->application->billing_address_unit,
                    'house_number' => $this->application->billing_street_number,
                    'street_name' => $this->application->billing_street_name_only,
                    'street_type' => $this->application->billing_street_type,
                    'postcode' =>  $this->application->billing_postcode,
                    'suburb' => $this->application->billing_city,
                    'region' => $this->getMappedState($this->application->billing_state),
                ],
            ],
            "utility_details" => [
                [
                    "utility_type" => $this->getMappedGasUtilityType($this->application->id),
                    "estimated_billing" => [
                        'cost' => $this->application->estimated_elec_billing_cost,
                        'period' => $this->application->estimated_elec_billing_period,
                    ],
                ],
                [
                    "utility_type" => $this->getMappedPowerUtilityType($this->application->id),
                    "meter_inaccessible_reason" =>
                        $this->application->additional_access_information . "," .
                        $this->getMappedUnrestrainedAnimal() . "," . $this->getMappedRenovation(),
                    "estimated_billing" => [
                        'cost' => $this->application->estimated_gas_billing_cost,
                        'period' => $this->application->estimated_gas_billing_period,
                    ],
                ]
            ],
            "vulnerabilities" => [
                'dependency_type' => $this->getMappedLifeSupport($this->application->id),
                'medical_reason' => $this->getMappedLifeSupportType($this->application->id),
                'medical_details_disclaimer_accepted_at' => $this->application->life_support_accepted_at,
            ],
            "payment_details" => [
                "card" => [
                    'card_type' => "mastercard",
                    'masked_card_number' => "xxxxxxxxxxxxx74",
                    'expiry_date' => "122022",
                    'cardholder_name' => "Messi",
                    'token' => "281174b44b44b397b38a",
                    'terms_and_conditions_accepted_at' => "2022-03-25T12:39:55+1100",
                    'preferred' => true,
                ]
            ],
            "eligible_for_concessions" => $this->getMappedConcession($this->application->eligible_for_concessions),
            "terms_and_conditions_accepted_at" => $this->application->terms_and_conditions_accepted_at,
            "promotion_code" => "HoodPS100%CarbonNeutral",
            "promotion_terms_and_conditions_accepted_at" => $this->application->promotion_terms_and_conditions_accepted_at,
        ];
    }


    private function getMappedProperty($property): string
    {
        return $property ? PowershopService::MAP_PROPERTY_TYPE[$property] : '';
    }

    private function getMappedState($state): string
    {
        return $state ? PowershopService::MAP_STATE[$state] : '';
    }

    private function getMappedGasUtilityType($id)
    {
        $type = ConnectionService::where('connection_application_id', $id)
            ->where('service_type', 'gas')->firstOrFail();

        if ($type->provider_name == "powershop")
        {
            return "gas";
        }
        return null;
    }

    private function getMappedPowerUtilityType($id)
    {
        $type = ConnectionService::where('connection_application_id', $id)
            ->where('service_type', 'power')->firstOrFail();

        if ($type->provider_name == "powershop")
        {
            return "electricity";
        }
        return null;
    }

    private function getMappedLifeSupport($id)
    {
        $application = ConnectionApplication::whereId($id)->firstOrFail();
        if ($application->is_gas_life_support === 1 || $application->is_power_life_support === 1){
            return "Life Support";
        }
        else {
            return null;
        }
    }

    private function getMappedLifeSupportType($id)
    {
        $application = ConnectionApplication::whereId($id)->firstOrFail();
        if ($application->is_gas_life_support === 1 && $application->is_power_life_support === 1)
        {
            return "Life support power and gas";
        }
        elseif ($application->is_gas_life_support === 1 && ($application->is_power_life_support === 0 || $application->is_power_life_support === null))
        {
            return "Life support gas";
        }
        elseif (($application->is_gas_life_support === 0 || $application->is_gas_life_support === null) && $application->is_power_life_support === 1)
        {
            return "Life support power";
        }
        else {
            return "Default";
        }
    }

    private function getMappedConcession($concession) : bool
    {
        return $concession ? PowershopService::MAP_ELIGIBLE_FOR_CONCESSION[$concession] : '';
    }

    private function getMappedUnrestrainedAnimal()
    {
        return $this->application->is_any_unrestrained_animal ? "unrestrained animal on the property" : null;
    }

    private function getMappedRenovation()
    {
        return $this->application->is_renovation_on ? "renovation going on" : null;

    }

}
