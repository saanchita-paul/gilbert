<?php

namespace App\Services\PowerShop;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function PHPUnit\Framework\matches;
use function PHPUnit\Framework\throwException;

/**
 *
 */
class CAFGenerationService
{

//    public function __construct(protected array $applicationIdList)
//    {
//    }

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

    /**
     * @param array $applicationIdList
     */
    public function __construct(array $applicationIdList)
    {

        $this->getPromotionCode();
        $this->applicationIdList = $applicationIdList;

        $this->fetchApplications();
        $this->mapApplications();
    }

    /**
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\IOException
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
            ->with('connectionServices','identification','authorizedPerson')
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
                $this->mappedApplicationList[] = [
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
                    'Electricity Promo' => $this->getPromo($app->state, ConnectionService::TYPE_ELECTRICITY),
                    'Gas Promo' => $this->getPromo($app->state, ConnectionService::TYPE_GAS),
                    'Electricity Already On? (Y/N)' => $this->checkElectricity($app),
                    'Meter Number(s)' => null,
                    'Any Hazards' => $this->getHazard($app->is_any_unrestrained_animal, $app->is_renovation_on),
                    'Any Access Requirements?' => $this->getAccessReq($app->is_access_require, $app->additional_access_information),
                    'Life Support/Sensitive Load' => $this->getLifeSensitive($app),
                    'Advised Main Switch Needs Turning Off?' => 'Yes',
                    'Safety Certificate Required?' => 'No',
                    'Token' => '',
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
        elseif ($app->is_power_life_support == 1){
            return 'Life support elec';
        }
        else{
            return 'Life support gas';
        }
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
     * @return string
     * @throws \Exception
     */
    private function getSignUpType($app): string
    {

        $services = $app->connectionServices->pluck('service_type')->toArray();

        if(in_array(ConnectionService::TYPE_GAS, $services ) && in_array(ConnectionService::TYPE_ELECTRICITY, $services )){
            return 'Two Fuel';
        } elseif(in_array(ConnectionService::TYPE_ELECTRICITY, $services )){
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
        if(is_null($is_renovation_on) && !empty(trim($is_renovation_on))) {
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
     * this api may take state for state wise promotion code
     * but if call without state it will give all the promotion code
     */
    private function getPromotionCode()
    {
        try {
            $chatbotUri = 'https://demo.chatbot.hood.ai/';
//            $chatbotUri = config('bot.root_url');
            $url = $chatbotUri.'hood-dashboard/api/power-shop/promo-code';
            $response = Http::get($url);
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


        foreach ($promotionData as $data)
        {

            foreach ($data as $data1) {

                $this->gasPromotionData[$data1['state']] = $data1['gas_promo_code'];
                $this->gasPromotionData[$data1['state']] = $data1['elec_promo_code'];
            }
        }

        info('data',   $this->gasPromotionData);
    }

    /**
     * @param $state
     * @param string $service
     * @return null|string
     */
    private function getPromo($state, string $service) : null| string
    {

        $state = $this->stateMap($state);
        return match($service) {
            ConnectionService::TYPE_GAS => $this->gasPromotionData[$state] ?? '' ,
            ConnectionService::TYPE_ELECTRICITY => $this->elePromotionData[$state] ?? '' ,
            default => ''
        };
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

}
