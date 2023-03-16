<?php

namespace App\Services\ApplicationCafService;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Utility\StateMapService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OriginExporter
{

    /* @var $applicationList */
    private $applicationList;

    /**
     * @var array
     */
    private array $mappedApplicationList = [];

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $chatbotUri;

    private $campaignInfo ;

    public const HAS_UNRESTING_ANIMAL = 1;

    /**
     * @param array $applicationIdList
     */
    public function __construct($applicationList)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->applicationList = $applicationList;
        $this->loadCampaignData();
        $this->mapApplications();

    }

    /**
     * map application for generating caf file
     */
    public function mapApplications()
    {
        $selectedId = [];
        foreach ($this->applicationList as $app) {
            try{
                $this->mappedApplicationList[] = [
                    'Broker Name' => 'HOOD',
                    'Origin Receive Date' => '',
                    'Date of Sale' => $this->setDateOfSales($app),
                    'Reference ID' => $app->sales_reference_id,
                    'Business Name' => '',
                    'ABN Identification Type' => '',
                    'ABN' => '',
                    'Account Holder - Title' => $app->title,
                    'Account Holder - First Name' => $app->first_name,
                    'Account Holder - Last Name' => $app->last_name,
                    'Account Holder - Date Of Birth' => date('d/m/Y', strtotime($app->dob)),
                    'Account Holder - Home Phone Number' => $app->homephone,
                    'Account Holder - Mobile Phone Number' => $app->phone,
                    'Account Holder - Email Address' => $app->email,
                    'Account Holder - Concession Card - Type' => $this->getConcessionCardType($app),
                    'Account Holder - Concession Card - Number' => $app->concession_card_number,
                    'Account Holder - Concession Card - Start dates' => $app->concession_start_date,
                    'Account Holder - Concession Card - End dates' => $app->concession_end_date,
                    'DRV Identification Type' => $this->getIDType($app),
                    'ID Number' => $this->getIDNumber($app),
                    'ID Expiry' => $this->getExpiryDate($app),
                    'ID State' => $this->getIdState($app),
                    'Joint Account Holder - First Name' => $this->getSecondFirstName($app),
                    'Joint Account Holder - Last Name' => $this->getSecondLastName($app),
                    'Joint Account Holder - Date Of Birth' => $this->getSecondDob($app),
                    'Joint Account Holder - Home Phone Number' => '',
                    'Joint Account Holder - Mobile Phone Number' => $this->getSecondPhone($app),
                    'Joint Account Holder - Email Address' => $this->getSecondEmail($app),
                    'Supply Address - Lot Number' => '',
                    'Supply Address - Unit/Flat/Shop Number' => $app->unit_number,
                    'Supply Address - Street Number' => $app->street_number,
                    'Supply Address - Street Name' => $app->street_name_only,
                    'Supply Address - Street Type' => $app->street_type,
                    'Supply Address - Suburb' => $app->city,
                    'Supply Address - State' => $app->state,
                    'Supply Address - Postcode' => $app->postcode,
                    'MSATS Address - Lot Number' => '',
                    'MSATS Address - Unit/Flat/Shop Number' => '',
                    'MSATS Address - Street Number' => '',
                    'MSATS Address - Street Name' => '',
                    'MSATS Address - Street Type' => '',
                    'MSATS Address - State' => '',
                    'MSATS Address - Suburb' => '',
                    'MSATS Address - Postcode' => '',
                    'Mailing Address - Unit/Flat/Shop Number' => $app->billing_unit_number,
                    'Mailing Address - Street Number' => $app->billing_street_number,
                    'Mailing Address - Street Name' => $app->billing_street_name_only,
                    'Mailing Address - Street Type' => $app->billing_street_type,
                    'Mailing Address - PO Box' => '',
                    'Mailing Address - Suburb' => $app->billing_city,
                    'Mailing Address - State' => $app->billing_state,
                    'Mailing Address - Postcode' => $app->billing_postcode,
                    'Email - Billing' => $this->checkEmailBilling($app),
                    'Email - Fulfilment' => $this->getFulfillment($app),
                    'Life Support Flag Requirement' => $this->checkLifeSupport($app),
                    'Life Support Note' => '',
                    'Sale Type' => 'Move in',
                    'Nominated MOVE IN DATE' => date('d/m/Y', strtotime($app->moving_date)),
                    'Electricity NMI' => $app->nmi,
                    'Electricity Meter Number' => '',
                    'Electricity Campaign Code' => $this->getCampaignCode($app->state, ConnectionService::TYPE_ELECTRICITY),
                    'Electricity Plan' => $this->getElectricityPlanName($app),
                    'Electricity Green Option chosen' => $this->checkOptOption($app),
                    'Gas MIRN' => $app->mirn,
                    'Gas Meter Number' => '',
                    'Gas Campaign Code' => $this->getCampaignCode($app->state, ConnectionService::TYPE_GAS),
                    'Gas Plan' => $this->getGasPlanName($app),
                    'Electrical Work Planned (VIC ONLY)' => $this->checkElectricalPlan($app),
                    'Site access and hazard information' => $this->checkHazard($app),
                    'Special Instructions' => '',
                    'Term' => '',
                    'Network' => '',
                    'Marketing Opt Out' => $this->checkEmailBilling($app),
                    'Fuels selected' => $this->getFuelType($app),
                    'BP' => '',
                    'Business Agreement - Electricity' => '',
                    'SAP Contract Number - Electricity' =>'',
                    'Business Agreement - Gas' => '',
                    'SAP Contract Number - Gas' => '',
                    'Electricity Status' => '',
                    'Electricity Reason' => '',
                    'Electricity Notes (Offshore)' => '',
                    'Electricity Notes (Onshore)' => '',
                    'Gas Status' => '',
                    'Gas Reason' => '',
                    'Gas Notes (Offshore)' => '',
                    'Gas Notes (Onshore)' => '',
                    'Close_Date' => '',

                ];
                $selectedId[] = $app->id;

            } catch (\Exception $exception) {
                Log::error($exception->getMessage());

            }

        }
        ConnectionApplication::whereIn('id', $selectedId)->update(['is_generated_caf' => true]);
    }

    /**
     * @param $app
     * @return string|null
     */
    private function getConcessionCardType($app){

        return match($app->concession_card_type) {
            'DVA' => 'DVA Health',
            'HCC' => 'Health Care Card',
            'PCC' => 'Pensioner Concession',
            'QSC'=> 'Queensland Seniors',
            default => null
        };

    }

    /**
     * @param $app
     * @return mixed
     */
    private function getIDNumber($app): mixed
    {
        return $app->identification?->card_number;
    }

    /**
     * @param $app
     * @return string
     */
    private function getExpiryDate($app)
    {
        return date('d-M', strtotime($app->identification?->expire_date));
    }

    /**
     * @param $app
     * @return null
     */
    private function getIdState($app)
    {
        return $app->identification?->expire_date;
    }

    /**
     * @param $app
     * @return string|null
     */
    private function getIDType($app): ?string
    {
        return match($app->identification?->type) {
            1 => 'passport',
            2 => 'driving licence',
            3 => 'medicare',
            default => null
        };
    }

    /**
     * @param $app
     * @return mixed
     */
    private function getSecondFirstName($app): mixed
    {
        return $app->authorizedPerson?->first_name;
    }

    /**
     * @param $app
     * @return mixed
     */
    private function getSecondLastName($app): mixed
    {
        return $app->authorizedPerson?->last_name;
    }

    /**
     * @param $app
     * @return string
     */
    private function getSecondDob($app)
    {
        return $app->authorizedPerson?->dob ? date('d/m/Y', strtotime($app->authorizedPerson?->dob)) : null;
    }

    /**
     * @param $app
     * @return null
     */
    private function getSecondPhone($app)
    {
        return $app->authorizedPerson?->phone;
    }

    /**
     * @param $app
     * @return null
     */
    private function getSecondEmail($app)
    {
        return $app->authorizedPerson?->email;
    }

    /**
     * @param ConnectionApplication $app
     * @return string
     * @throws \Exception
     */

    private function getFuelType(ConnectionApplication $app) : string
    {
        $services = $app->connectionServices;

        if(!empty($services)) {
            $serviceType = $services->filter(function($type) {
                return $type->provider_name === 'origin';
            })->pluck('service_type')->toArray();
        }
        if(in_array(ConnectionService::TYPE_GAS, $serviceType )
            && in_array(ConnectionService::TYPE_ELECTRICITY, $serviceType )) {
            return 'electricity and gas';
        } elseif(in_array(ConnectionService::TYPE_ELECTRICITY, $serviceType )) {
            return 'electricity';
        } elseif(in_array(ConnectionService::TYPE_GAS, $serviceType )) {
            return 'gas';
        } else {
            \Log::alert('Something Wrong');
            return '';

        }
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkEmailBilling($app): ?string
    {
        return $app->is_email_marketing ? 'No': 'Yes';
    }


    /**
     * @param $app
     * @return string|null
     */
    private function getFulfillment($app): ?string
    {
        return $app->is_email_billing?'EMAIL':'POST';
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkLifeSupport($app): ?string
    {
        return $app->is_power_life_support? 'Yes': 'No';
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkOptOption($app): ?string
    {
        return $app->ea_go_neutral? 'Yes': 'No';
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkHazard($app): ?string
    {
        $hazards = [];
        if($app->additional_access_information){
            $hazards[] = $app->additional_access_information;
        }
        if($app->inspection_time){
            $hazards[] = $app->inspection_time;
        }
        if($app->is_any_unrestrained_animal && $app->is_any_unrestrained_animal === self::HAS_UNRESTING_ANIMAL) {
            $hazards[] = 'Dog on property';
        }
        return implode(", ", $hazards);
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkElectricalPlan($app): ?string
    {
        return $app->is_access_require? 'Yes': 'No';
    }

    /**
     * @param ConnectionApplication $app
     * @return string
     */
    private function getElectricityPlanName(ConnectionApplication $app):string
    {
        $electricity = $app->connectionServices->filter(function ($svc) {
            $svc->service_type === ConnectionService::TYPE_ELECTRICITY;
            })->first();

        if(empty($electricity) || empty($electricity->plan_type)) {
            return '';
        }
        return $electricity->plan_type;
    }

    /**
     * @param ConnectionApplication $app
     * @return string
     */
    private function getGasPlanName(ConnectionApplication $app): string
    {
        $gas = $app->connectionServices->filter(function ($svc) {
            $svc->service_type === ConnectionService::TYPE_GAS;
        })->first();

        if(empty($gas)) {
            return '';
        }
        return $gas->plan_type ?? "";
    }

    private function getCampaignCode(string $state, string $fuelType = '')
    {
        $fuels = ['gas' => 'Natural Gas', 'power' => 'Electricity'];
        $fuelType = $fuels[$fuelType];
        $state = StateMapService::getShortName($state);

        foreach($this->campaignInfo  as $ci) {
            if( $ci['state'] === $state && $ci['fuel_type'] === $fuelType) {
                $filteredCode = $ci['campaign_code'];
            }
        }
        return $filteredCode ?? "";
    }

    public function getCollection(): \Illuminate\Support\Collection
    {
        return collect($this->mappedApplicationList);
    }

    /**
     * @param mixed $app
     * @return string
     */
    private function setDateOfSales(mixed $app):string
    {

        $salesDate = $app->connectionServices->filter(function ($svc) {
            return in_array($svc->service_type, [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
                && !empty($svc->submitted_at);
        })?->sortByDesc('submitted_at')
            ->pluck('submitted_at');

        return !empty($salesDate)? date('d/m/Y', strtotime($salesDate)) : '';
    }


    private function loadCampaignData()
    {
        $campaignUrl = config('bot.root_url').'/hood-dashboard/api/origin-campaign-codes';
        $headers = ["Accept" => "application/json",];
        $response = Http::withOptions([
            "headers" => $headers,
            "verify" => false,
        ])->get($campaignUrl);

        $response->throw();
        $this->campaignInfo = json_decode($response->body(), true);
    }


}
