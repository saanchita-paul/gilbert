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
        1 => 'Residential',
        2 => 'Business',
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

            $url = config('powershop.base_url').config('powershop.send_customer_data_url');

            $response = Http::put($url, $this->getCustomerData());

            return json_decode($response->body(), true);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function getCustomerData(): array
    {
        return [
            'account_setup[account_type]' => $this->getMappedProperty($this->application->property_type),

            'account_holder[title]' => $this->application->title,
            'account_holder[first_name]' => $this->application->first_name,
            'account_holder[last_name]' => $this->application->last_name,
            'account_holder[date_of_birth]' => $this->application->dob,
            'account_holder[phone_number]' => $this->application->phone,

            'login[email]' => $this->application->email,

            'secondary_account_holders[title]' => $this->application->authorizedPerson?->title,
            'secondary_account_holders[first_name]' => $this->application->authorizedPerson?->first_name,
            'secondary_account_holders[last_name]' => $this->application->authorizedPerson?->last_name,
            'secondary_account_holders[date_of_birth]' => $this->application->authorizedPerson?->dob,
            'secondary_account_holders[phone_number]' => $this->application->authorizedPerson?->phone,

            'property_information[current_situation]' => "Moving",
            'property_information[supply_address].house_number' => $this->application->address_unit . $this->application->street_number,
            'property_information[supply_address].street_name' => $this->application->street_name,
            'property_information[supply_address].street_type' => $this->application->street_type,
            'property_information[supply_address].postcode' => $this->application->postcode,
            'property_information[supply_address].suburb' => $this->application->city,
            'property_information[supply_address].region' => $this->getMappedState($this->application->state),

            'property_information[postal_address].house_number' => $this->application->billing_address_unit . $this->application->billing_street_number,
            'property_information[postal_address].street_name' => $this->application->billing_street_name,
            'property_information[postal_address].street_type' => $this->application->billing_street_type,
            'property_information[postal_address].postcode' => $this->application->billing_postcode,
            'property_information[postal_address].suburb' => $this->application->billing_city,
            'property_information[postal_address].region' => $this->getMappedState($this->application->billing_state),

//            'utility_details[0].utility_type' => $this->getMappedService($this->application->connectionServices?->pluck('service_type')->toArray()),
            'utility_details[0].estimated_billing.cost' => $this->application->estimated_elec_billing_cost,
            'utility_details[0].estimated_billing.period' => $this->application->estimated_elec_billing_period,
//            'utility_details[1].utility_type' => $this->getMappedService($this->application->connectionServices?->pluck('service_type')->toArray()),
            'utility_details[1].estimated_billing.cost' => $this->application->estimated_gas_billing_cost,
            'utility_details[1].estimated_billing.period' => $this->application->estimated_gas_billing_period,

            'vulnerabilities[dependency_type]' => $this->getMappedLifeSupport($this->application),
            'vulnerabilities[medical_reason]' => $this->getMappedLifeSupportType($this->application),
            'vulnerabilities[medical_details_disclaimer_accepted_at]' => $this->application->life_support_accepted_at,

            'payment_details[card].card_type' => "mastercard",
            'payment_details[card].masked_card_number' => "xxxxxxxxxxxxx74",
            'payment_details[card].expiry_date' => "122022",
            'payment_details[card].cardholder_name' => "Messi",
            'payment_details[card].token' => "281174b44b44b397b38a",
            'payment_details[card].terms_and_conditions_accepted_at' => "preferred",
            'payment_details[card].preferred' => true,

            'eligible_for_concessions' => $this->getMappedConcession($this->application->eligible_for_concessions),
            'terms_and_conditions_accepted_at' => $this->application->terms_and_conditions_accepted_at,


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

//    private function getMappedService($services): array
//    {
//        $data = [];
//        foreach ( $services as $service) {
//            if (isset(PowershopService::MAP_SERVICE[$service])) {
//                $data[] =  PowershopService::MAP_SERVICE[$service];
//            }
//        }
//        return $data;
//    }

    private function getMappedLifeSupport($id)
    {
        $application = ConnectionApplication::whereId($id)->firstOrFail();
        if ($application->is_gas_life_support === 1 || $application->is_power_life_support === 1){
            return true;
        }
        else {
            return false;
        }
    }

    private function getMappedLifeSupportType($id)
    {
        $application = ConnectionApplication::whereId($id)->firstOrFail();
        if ($application->is_gas_life_support === 1 && $application->is_power_life_support === 1)
        {
            return "Life support power and gas";
        }
        elseif ($application->is_gas_life_support === 1 && $application->is_power_life_support === 0)
        {
            return "Life support gas";
        }
        elseif ($application->is_gas_life_support === 0 && $application->is_power_life_support === 1)
        {
            return "Life support power";
        }
    }

    private function getMappedConcession($concession): string
    {
        return $concession ? PowershopService::MAP_ELIGIBLE_FOR_CONCESSION[$concession] : '';
    }

}
