<?php

namespace CafExporter\Services;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
    private array $gasPromotionData;
    private array $elePromotionData;
    private $cafToken;
    private $chatbotUri;


    public function __construct(array $applicationIdList)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->getPromotionCode();
        $this->applicationIdList = $applicationIdList;
        // $this->cafToken = $this->getPowerShopCafToken();

        $this->fetchApplications();
        $this->mapApplications();
    }

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
                    'Nominated MOVE IN DATE' => date('d/m/Y', strtotime($app->moved_at)),
                    'Electricity NMI' => $app->nmi,
                    'Electricity Meter Number' => '',
                    'Electricity Campaign Code' => $this->getElectricityCampaignCode($app),
                    'Electricity Plan' => $this->getElectricityPlanName($app),
                    'Electricity Green Option chosen' => $this->checkOptOption($app),
                    'Gas MIRN' => $app->mirn,
                    'Gas Meter Number' => '',
                    'Gas Campaign Code' => $this->getGasCampaignCode($app),
                    'Gas Plan' => $this->getGasPlanName($app),
                    'Electrical Work Planned (VIC ONLY)' => $this->checkElectricalPlan($app),
                    'Site access and hazard information' => $this->checkHazard($app),
                    'Special Instructions' => '',
                    'Term' => '',
                    'Network' => '',
                    'Marketing Opt Out' => $this->checkEmailBilling($app),
                    'Fuels selected' => $this->getFuel($app),
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


                    'Brand' => 'PowerShop',
                    'Channel ID' => 'Hood Move Tech',
                    'Customer Type' => 'Residential',
                    'NMI' => $app->nmi,
                    'MIRN' => $app->mirn,
                    'Connection Date' => date('m/d/Y', strtotime($app->moving_date)),
                    'Type of Sale' => 'Moving',
                    'Signup Type' => $this->getSignUpType($app),
                    'Title' => $app->title,
                    'First Name' => $app->first_name,
                    'Last Name' => $app->last_name,
                    'Date of Birth' => date('m/d/Y', strtotime($app->dob)),
                    'Business Name' => null,
                    'Phone - Home' => $app->homephone,
                    'Phone - Office' => null,
                    'Phone - Mobile' => $app->phone,
                    'E-mail' => $app->email,
                    'ABN' => null,
                    'ACN' => null,
                    'ID Number' => $this->getIDNumber($app),
                    'ID Expiry date' => $this->getExpiryDate($app),
                    'Type of ID' =>  $this->getIDType($app),
                    'Concession Card Number' => null,
                    'Concession Card Type' => $this->getConcessionCardType($app),
                    'Concession Card Expiry Date' => null,
                    'Name on Concession Card' => null,
                    'Second Person Title' =>  $this->getSecondTitle($app),
                    'Second First Name' => $this->getSecondFirstName($app),
                    'Second Last Name' => $this->getSecondLastName($app),
                    'Second Person DOB' => $this->getSecondDob($app),
                    'Supply Address' => $this->getSupplyAddress($app),
                    'Supply Suburb' => $app->city,
                    'State/Territory' => $app->state,
                    'Postal Code' => $app->postcode,
                    'Mailing Address' => $this->getMailingAddress($app),
                    'Mailing Suburb' => $app->billing_city,
                    'Mailing State/Territory' => $app->billing_state,
                    'Mailing Postal Code' => $app->billing_postcode,
                    'Owner/Renter' => $this->getTenancyType($app),
                    'Electricity Promo' => $this->getPromo($app, ConnectionService::TYPE_ELECTRICITY),
                    'Gas Promo' => $this->getPromo($app, ConnectionService::TYPE_GAS),
                    'Electricity Already On? (Y/N)' => $this->checkElectricity($app),
                    'Meter Number(s)' => null,
                    'Any Hazards' => $this->getHazard($app->is_any_unrestrained_animal, $app->is_renovation_on),
                    'Any Access Requirements?' => $this->getAccessReq($app->is_access_require, $app->additional_access_information),
                    'Life Support/Sensitive Load' => $this->getLifeSensitive($app),
                    'Advised Main Switch Needs Turning Off?' => 'Yes',
                    'Safety Certificate Required?' => 'No',
                    'Token' => $cafToken,
                    'Electricity Offer Status' => null,
                    'Electricity Reference Number' => null,
                    'Electricity Rejection/Incomplete Reason' => null,
                    'Gas Offer Status' => null,
                    'Gas Reference Number' => null,
                    'Gas Rejection/Incomplete Reason' => null,
                    'Other Comments' => null,

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

    private function getSecondPhone($app)
    {
        return $app->authorizedPerson?->phone;
    }
    private function getSecondEmail($app)
    {
        return $app->authorizedPerson?->email;
    }

    /**
     * @param ConnectionApplication $app
     * @return string
     * @throws \Exception
     */
    private function getSignUpType(ConnectionApplication $app): string
    {
        $services = $app->connectionServices;

        $serviceType = $services->filter(function($type) {
            return $type->provider_name === 'powershop';
        })?->pluck('service_type')->toArray();

        if(in_array(ConnectionService::TYPE_GAS, $serviceType )
            && in_array(ConnectionService::TYPE_ELECTRICITY, $serviceType )){
            return 'Two Fuel';
        } elseif(in_array(ConnectionService::TYPE_ELECTRICITY, $serviceType )){
            return 'Electricity';
        } else{
            return '';
            throw new \Exception('Only gas not supported!');
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

    private function getPowerShopCafToken($app): string
    {
        return (string) $app?->powershopPaymentInfo?->px_dps_billing_id;
    }

    private function checkEmailBilling($app): ?string
    {
        return $app->is_email_marketing? 'Yes': 'No';
    }

    private function getFulfillment($app)
    {
        switch ($app->is_email_billing) {
            case Plan::BILLING_PREFERENCE_EMAIL:
                return 'Email';
            case Plan::BILLING_PREFERENCE_CONNECTION_ADDRESS:
                return 'Post';
            default:
                Log::error('Unknown');
                return  '';
        }

    }

//    private function getFulfillment($app): ?string
//    {
////        return match($app->is_email_billing) {
////            1 => 'passport',
////            2 => 'driving licence',
////            3 => 'medicare',
////            default => null
////        };
//        return $app->is_access_require? 'Yes': 'No';
//    }
}
