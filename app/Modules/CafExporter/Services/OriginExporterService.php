<?php

namespace CafExporter\Services;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\OriginPlan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Origin\Services\StoreProductInfoAPI;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 *
 */
class OriginExporterService
{
    /**
     * @var array
     */
    private array $applicationIdList;
    /**
     * @var
     */
    private $applicationList;
    /**
     * @var array
     */
    private array $mappedApplicationList;
    /**
     * @var array
     */
    private array $gasPromotionData;
    /**
     * @var array
     */
    private array $elePromotionData;
    /**
     * @var
     */
    private $cafToken;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $chatbotUri;

    private array $campaignInfo;

    /**
     *
     */
    public const HAS_UNRESTING_ANIMAL = 1;
    /**
     *
     */
    public const NO_UNRESTING_ANIMAL = 0;

    /**
     * @param array $applicationIdList
     */
    public function __construct(array $applicationIdList)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->getPromotionCode();
        $this->applicationIdList = $applicationIdList;
        // $this->cafToken = $this->getPowerShopCafToken();

        $this->fetchApplications();
        $this->mapApplications();
    }

    /**
     * @return StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function downloadCAF(): StreamedResponse
    {
        return FastExcel::data(collect($this->mappedApplicationList))->download(now()->unix().'.xlsx');
    }

    /**
     * @return void
     */
    private function fetchApplications(): void
    {
        $this->applicationList = ConnectionApplication::query()->whereIn('id', $this->applicationIdList)
            ->with('connectionServices','identification','authorizedPerson', 'powershopPaymentInfo')
            ->get();
    }


    /**
     * @param mixed $get
     */

    public function mapApplications()
    {
        $base = config('bot.root_url');
        $endpoint = '/hood-dashboard/api/origin-campaign-codes';
        $url = $base . $endpoint;
        $headers = [
            "Accept" => "application/json",
        ];
        $response = Http::withOptions([
            "headers" => $headers,
            "verify" => false,
        ])->get($url);

        $response->throw();

        $this->campaignInfo = json_decode($response->body(), true);

        $selectedId = [];
        foreach ($this->applicationList as $app) {
            try{
                $cafToken = $this->getPowerShopCafToken($app);
                $this->mappedApplicationList[] = [
                    'Broker Name' => 'HOOD',
                    'Origin Receive Date' => '',
                    'Date of Sale' => date('d/m/Y', strtotime($app->submitted_at)),
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
                    'Mailing Address - Street Name' => $app->billing_street_name,
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
                info('Data file to export due to', [$exception->getMessage()]);

            }

        }
        ConnectionApplication::whereIn('id', $selectedId)->update(['is_generated_caf' => true]);
    }

    /**
     * @param $app
     * @return string|null
     */
    private function getTenancyType($app)
    {
        return match($app->tenancy_type) {
            1 => 'Renter',
            2 => 'Home Owner',
            default => null
        };
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkElectricity($app)
    {
        return match($app->has_electricity) {
            1 => 'Yes',
            2 => 'No',
            default => null
        };
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
     * @return string
     */
    private function getLifeSensitive($app): string
    {
        if ($app->is_gas_life_support == 1 && $app->is_power_life_support == 1){
            return 'Life support elec and gas';
        }
        elseif ($app->is_power_life_support === 1){
            return 'Life support elec';
        }
        else if($app->is_gas_life_support === 1){
            return 'Life support gas';
        }
        return '';
    }

    /**
     * @param $app
     * @return string
     */
    private function getSupplyAddress($app): string
    {
        if($app->unit_number == ''){
            return $app->street_number .' , '. $app->street_name_only .' , '. $app->street_type;
        }
        elseif ($app->street_number == ''){
            return $app->unit_number. ' , ' . $app->street_name_only .' , '. $app->street_type;
        }
        elseif ($app->street_name_only == ''){
            return $app->unit_number. ' , ' .$app->street_number .' , '. $app->street_type;
        }
        elseif ($app->street_type == ''){
            return $app->unit_number. ' , ' .$app->street_number .' , '. $app->street_name_only ;
        }
        else{
            return $app->unit_number. ' , ' .$app->street_number .' , '. $app->street_name_only .' , '. $app->street_type;
        }
    }

    /**
     * @param $app
     * @return string
     */
    private function getMailingAddress($app): string
    {
        if($app->billing_unit_number == ''){
            return $app->billing_street_number .' , '. $app->billing_street_name_only .' , '. $app->billing_street_type;
        }
        elseif ($app->billing_street_number == ''){
            return $app->billing_unit_number. ' , ' . $app->billing_street_name_only .' , '. $app->billing_street_type;
        }
        elseif ($app->billing_street_name_only == ''){
            return $app->billing_unit_number. ' , ' .$app->billing_street_number .' , '. $app->billing_street_type;
        }
        elseif ($app->billing_street_type == ''){
            return $app->billing_unit_number. ' , ' .$app->billing_street_number .' , '. $app->billing_street_name_only ;
        }
        else{
            return $app->billing_unit_number. ' , ' .$app->billing_street_number .' , '. $app->billing_street_name_only .' , '. $app->billing_street_type;
        }

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
    private function getSecondTitle($app): mixed
    {
        return $app->authorizedPerson?->title;
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
     * @param $is_any_unrestrained_animal
     * @param $is_renovation_on
     * @return string
     */
    private function getHazard($is_any_unrestrained_animal, $is_renovation_on): string
    {
        $hasrestineAnymal = false;
        $hazard = '';
        if( !is_null($is_any_unrestrained_animal) && !empty(trim($is_any_unrestrained_animal)) ) {
            $hazard =  'Animal on property';
            $hasrestineAnymal = true;
        }
        if(!is_null($is_renovation_on) && !empty(trim($is_renovation_on))) {
            if($hasrestineAnymal) {
                return $hazard .', '. 'renovation going on';
            }
        }
        return $hazard;

    }

    /**
     * @param $is_access_require
     * @param $additional_access_information
     * @return string
     */
    private function getAccessReq($is_access_require, $additional_access_information): string
    {
        if($is_access_require) {
            return  'Yes, '. $additional_access_information;
        } else {
            return 'No';
        }

    }


    /**
     * call chatbot api for finding promotion code
     */
    private function getPromotionCode()
    {
        try {
            $url = $this->chatbotUri.'/hood-dashboard/api/power-shop/promo-code';

            if (config('app.env') == 'local') $response = Http::withOptions(['verify' => false,])->get($url);
            else $response = Http::get($url);

            if($response->status() == 200) {
                $this->mapPromotionCode(json_decode($response->body(), true));
            }
        } catch (\Exception $e) {
            Log::warning('No promotion code is found'.$e->getMessage());
        }
    }

    /**
     * @param array|null $promotionData
     */
    private function mapPromotionCode(?array $promotionData): void
    {
        $this->gasPromotionData = data_get($promotionData, 'gas', []);
        $this->elePromotionData = data_get($promotionData, 'electricity', []);
    }

    /**
     * @param $state
     * @param string $service
     * @return null|string
     */
    private function getPromo($app, string $service) : null| string
    {
        $promo = '';

        $state = $app->state;
        $postcode = $app->postcode;

        $conService = ConnectionService::where('connection_application_id', $app->id)
            ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
            ->where('service_type', $service)
            ->first();

        if ($conService) {
            $plan_name = $conService->plan_type;

            if ($service == ConnectionService::TYPE_ELECTRICITY && !empty($this->elePromotionData)){
                $nmi_prefix = substr($app->nmi, 0, 3);

                foreach($this->elePromotionData as $data){
                    if ((in_array($state, $data['state']) || in_array($this->stateMap($state), $data['state'])) &&
                        in_array($plan_name, $data['plan_name']) &&
                        in_array($postcode, $data['postcode'])
                    ){
                        if (empty($promo) || in_array($nmi_prefix, $data['nmi_prefix'])){
                            $promo = $data['promo_code'];
                        }
                    }
                }
            }

            if ($service == ConnectionService::TYPE_GAS && !empty($this->gasPromotionData)){

                foreach($this->gasPromotionData as $data){
                    if ((in_array($state, $data['state']) || in_array($this->stateMap($state), $data['state'])) &&
                        in_array($plan_name, $data['plan_name']) &&
                        in_array($postcode, $data['postcode'])
                    ){
                        $promo = $data['promo_code'];
                    }
                }
            }
        }

        return $promo;
    }

    /**
     * @param $state
     * @return mixed|string
     */
    private function stateMap($state)
    {
        $stateList = ['New South Wales'=>'NSW','Victoria'=>'VIC','Queensland'=>'QLD',
            'South Australia'=>'SA','Northern Territory'=>'NT','TAS'=>'Tasmania','ACT'=>'Australian Capital Territory','WA' => 'Western Australia'];
        if(array_key_exists($state, $stateList))
        {
            return $stateList[$state];
        }
        return $state;

    }

    /**
     * @param $app
     * @return string
     */
    private function getPowerShopCafToken($app): string
    {
        return (string) $app?->powershopPaymentInfo?->px_dps_billing_id;
    }

    /**
     * @param $app
     * @return string|null
     */
    private function checkEmailBilling($app): ?string
    {
        return $app->is_email_marketing? 'Yes': 'No';
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
        switch ($app->is_any_unrestrained_animal) {
            case self::HAS_UNRESTING_ANIMAL:
                return 'dog on property';
            case self::NO_UNRESTING_ANIMAL:
                return '';
            default:
                Log::error('Unknown Hazard');
                return  '';
        }
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
    private function getElectricityPlanName(ConnectionApplication $app): string
    {
        $electricity = ConnectionService::query()->where('connection_application_id', $app->id)
            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)->first();
        if(empty($electricity)) {
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
        $gas = ConnectionService::query()->where('connection_application_id', $app->id)
            ->where('service_type', ConnectionService::TYPE_GAS)->first();
        if(empty($gas)) {
            return '';
        }
        return $gas->plan_type;
    }

    private function getCampaignCode(string $state, string $fueltype = '')
    {
        $filteredCode = array_filter($this->campaignInfo, function ($data) use ($state) {
            return $data['state'] === $state;
        });
        if(count($filteredCode)) {
            return $filteredCode[0]->campaign_code;
        }
        return  '';
    }


}
