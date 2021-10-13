<?php

namespace App\Services\Agency\CafFile;

//use App\MovingUtilityData;
//use App\Plan;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use App\Models\Identification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CAFDataMappingService implements FromCollection, WithHeadings
{

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $collection;

    private $chatbotUri;



    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->chatbotUri = config('bot.root_url');
//        $this->chatbotUri = 'http://127.0.0.1:8000';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection->map(
            function (ConnectionApplication $utilityData) {

                /** @var ConnectionApplicationSecondaryACC $second_account_holder*/
                $second_account_holder = ConnectionApplicationSecondaryACC::query()
                    ->where('connection_application_id','=', $utilityData->id)
                    ->first();

                $services = ConnectionService::query()
                    ->where('connection_application_id','=',$utilityData->id)
                    ->pluck('service_type');
//                Log::info('second-holder', $second_account_holder->toArray());

                $idExpireDate = Carbon::parse($utilityData->identification->expire_date)->format("d/m/Y");
                return [
                    'vendor_id' => $utilityData->vendor_id,
                    'sale_date' => $this->getAgreeAt($utilityData),
                    'elec_source_code' =>$services->contains('power')? $this->getElectricitySourceCode($utilityData->plan_type, $utilityData->state): '',
                    'gas_source_code' => $services->contains('gas')? $this->getGasSourceCode($utilityData->plan_type, $utilityData->state): '',
                    'customer_type' => $utilityData->property_type == 1? 'RESI':'SME',
                    'offer_type' => 'ENE',
                    'connection_date' => Carbon::parse($utilityData->moving_date)->format("d/m/Y"),
                    'visual_inspection' => $utilityData->state === 'Queensland'?
                        $utilityData->has_electricity  != 1?'Y': 'N'
                        :'',
                    'electricity_already_on' => $utilityData->state === 'Queensland'?
                        (!empty($utilityData->has_electricity) &&
                    $utilityData->has_electricity)?'Y':'N'
                    :'',
                    'inspection_timeframe' => $utilityData->has_electricity  != 1?
                        $this->timeFrame($utilityData->inspection_time)
                        : '',

                    'special_instruction_for_access' => ($utilityData->state === 'Victoria')?($utilityData->is_renovation_on?'Y':'N'):'',
                    'renovation_current' => ($utilityData->state === 'Victoria')?($utilityData->is_renovation_on?'Y':'N'):'',
                    'renovation_privious' => ($utilityData->state === 'Victoria')?($utilityData->is_renovation_on?'Y':'N'):'',
                    'main_swith_off' => ($utilityData->state === 'Victoria')?($utilityData->is_renovation_on?'Y':'N'):'',

                    'business_name' => '', //todo
                    'business_abn' => '', //todo
                    'business_type' => '', //todo


                    //Personal Details
                    'first_account_holder_title' => $utilityData->title,
                    'first_account_holder_firstname' => $utilityData->first_name,
                    'first_account_holder_surname' => $utilityData->last_name,
                    'first_account_holder_dob' =>Carbon::parse($utilityData->dob)->format("d/m/Y"),
                    'phone_type' => $utilityData->phone_type == 1? 'Mobile,': 'Home',
                    'phone_number' => $utilityData->phone_type == 1?$utilityData->phone:$utilityData->homephone,
                    'email_welcome_consent' => $utilityData->is_email_billing == 1 ? 'Y' : 'N',
                    'email_billing' => $utilityData->is_email_billing == 1 ? 'Y' : 'N',
                    'email_address' => $utilityData->email,

                    // Identification Passport/Driving License etc
                    'id_firstname' => $utilityData->first_name,
                    'id_middle_name' => $utilityData->middle_name,
                    'id_surname' => $utilityData->last_name,
                    'id_type' => $utilityData->identification->type == Identification::TYPE_PASSPORT ?
                        'Passport' : ($utilityData->identification->type == 2 ?
                            'Driving License' : ($utilityData->identification->type == 3 ? 'Medicare' : '')),
                    'id_number' => $utilityData->identification->card_number,
                    'dl_expiry_date' => $utilityData->identification->type == 2?
                        $utilityData->identification->expire_date: '',
                    'passport_expiry_date' => $utilityData->identification->type == Identification::TYPE_PASSPORT?
                        $utilityData->identification->expire_date: '',
                    'medicare_expiry_date' => $utilityData->identification->type == 3? $utilityData->identification->expire_date: '',
                    'state_colour_country' => $this->getStateColourOrCountry($utilityData->identification),
                    'medicare_card_reference_number' => $utilityData->identification->special_number,
                    'primary_id_flag' => 'Yes',

                    //Secondary Profile Info
                    'second_account_holder_title'=> is_object($second_account_holder)? $second_account_holder->title: '',
                    'second_account_holder_first_name'=> is_object($second_account_holder)
                        ? $second_account_holder->first_name: '',
                    'second_account_holder_surname'=>is_object($second_account_holder)?
                        $second_account_holder->last_name: '',
                    'second_account_holder_dob' =>is_object($second_account_holder)?
                        Carbon::parse($second_account_holder->dob)->format("d/m/Y"): '',

                    // Supply Address
                    'supply_unit_or_flat_number' => $utilityData->unit_number,
                    'supply_street_number' => $utilityData->street_number,
                    'supply_street_name' => $utilityData->street_name,
                    'supply_street_type' => $utilityData->getRoadType(),
                    'supply_suburb' => $utilityData->city,
                    'supply_state' => $utilityData->state,
                    'supply_postcode' => $utilityData->postcode,

                    // Mailing Address.
                    'mailing_unit_or_flat_number' => $utilityData->unit_number,
                    'mailing_street_number' => $utilityData->street_number,
                    'mailing_street_name' => $utilityData->street_name,
                    'mailing_street_type' => $utilityData->getRoadType(),
                    'mailing_suburb' => $utilityData->city,
                    'mailing_state' => $utilityData->state,
                    'mailing_postcode' => $utilityData->postcode,

                    // EnergyAustralia Stuff
                    'nmi' => $utilityData->nmi,
                    'mirn' => $utilityData->mirn,
                    'premise_type' => $utilityData->property_type == 1? 'RESI':'SME',
                    'dpid' => '',
                    'fuel_elec' => $this->isServiceType('power', $utilityData->id)?'Y':'N',
                    'fuel_gas' =>  $this->isServiceType('gas', $utilityData->id)?'Y':'N',
                    'elec_plan' => $services->contains('power')? $this->getElectricitySourceCode($utilityData->plan_type, $utilityData->state):'',
                    'gas_plan' => $services->contains('gas')?$this->getGasSourceCode($utilityData->plan_type, $utilityData->state):'',
                    'green_energy'=> 'N',
                    'window_power' => 'N',
                    'payment_method' =>'',
                    'additional_comments' => '',
                    'elec_status' => '',
                    'gas_status' => '',
                    'elec_reason' => '',
                    'gas_reason' => '',
                    'quote_id_elec' => '',
                    'quote_id_gas' => '',
                    'go_neutral' => 'N',
                    'solar' => $utilityData->has_solar == 1 ? 'Y' : 'N',
                    'tariff_code' =>  '',
                    'buyback_rate' =>  $this->getBuyBackRate($utilityData->has_solar, $utilityData->state),
                ];
            }
        );
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Vendor ID',
            'Sale Date',
            'Elec Source Code',
            'Gas Source Code',
            'Customer Type',
            'Offer Type',
            'Connection Date',
            'Visual Inspection',
            'Electricity Already On? (Y/N)',
            'Inspection Timeframe',


            'Special Instructions for Access',
            'VIC Only - Renovation/Alterations at the property currently?',
            'VIC Only - Renovation/Alterations at the property (previously)?',
            'VIC ONLY - Mains Switch OFF',

            'Business Name',
            'ABN/ACN',
            'Business Type',

            //Personal Details
            'First Account Holder Title',
            'First Account Holder Firstname',
            'First Account Holder Surname',
            'First Account Holder DOB',
            'Phone Type',
            'Phone Number',
            'Email Welcome Consent Y/N',
            'Email Billing Y/N',
            'Email Address',

            // Identification Passport/Driving License etc
            'ID Firstname',
            'ID Middle name',
            'ID Surname',
            'ID Type',
//            'Passport Expiry date',
//            'Driver license Expiry date',
            'ID Number',
            'DL Expiry date',
            'Passport Expiry date',
            'Medicare Expiry date',
            'DRV State/Medicare Card Colour/Country of Issue',
            'Medicare Card Reference Number',
            'Primary Id Flag',

            //Secondary Profile Info
            'Second Account Holder Title',
            'Second Account Holder First name',
            'Second Account Holder Surname',
            'Second Account Holder DOB',

            // Supply Address
            'Supply Unit/Flat Number',
            'Supply Street Number',
            'Supply Street Name',
            'Supply  Street Type',
            'Supply Suburb',
            'Supply State',
            'Supply Postcode',

            // Mailing Address.
            'Mailing Unit/Flat Number',
            'Mailing Street/PO BOX Number',
            'Mailing Street Name',
            'Mailing Street Type/PO BOX',
            'Mailing Suburb',
            'Mailing State',
            'Mailing Postcode',

            // EnergyAustralia Stuff
            'NMI',
            'MIRN',
            'Premise Type',
            'DPID',
            'Fuel elec',
            'Fuel gas',
            'Elec Plan',
            'Gas Plan',
            'ACT Only - Green Energy',
            'Windpower',
            'Payment Method',
            'Additional Comments',
            'Elec Status',
            'Gas Status',
            'Elec  Reason',
            'Gas  Reason',
            'Quote ID - Elec',
            'Quote ID - Gas',
            'Go NeuTral',
            'Solar Y/N',
            'Tariff Code',
            'Buyback Rate',
        ];
    }

    /**
     * @return bool
     */
    public function hasData(): bool
    {
        return $this->collection->count() > 0;
    }

    /**
     * @return $this
     */
    public function updateRecords(): self
    {
        ConnectionApplication::query()->whereIn('id', $this->collection->pluck('id')->toArray())->update([
            'status' => ConnectionApplication::STATUS_EA_PROCESSINF,
        ]);
        return $this;
    }

    /**
     *
     * @return string
     */
    private function getAgreeAt(ConnectionApplication $caData)
    {
        if (!empty($caData->updated_at)) {
            return Carbon::parse($caData->updated_at)->format('d/m/Y');
        }
        return $caData->created_at->format('d/m/Y');
    }


    /**
     * @param Identification $identification
     * @return string
     */
    private function getStateColourOrCountry(Identification $identification)
    {

        if($identification->type == Identification::TYPE_PASSPORT)
        {
            return $identification->country;
        }
        if($identification->type == 2)
        {
            return $identification->state;
        }
        if($identification->type == 3)
        {
            return $identification->card_color;
        }
        return '';
    }

    private function isServiceType($service, $id)
    {
        /**
         * @var Collection $services
         */
        $services = ConnectionService::where('connection_application_id','=',$id)->pluck('service_type');
        return $services->contains($service);

    }
    private function getElectricitySourceCode($plan, $state)
    {
        $plan = ConnectionApplication::PLAN_TYPE_REVERSE_MAPPER[$plan];
        $state = $this->stateMap($state);

        try{
            Log::info('url', [$this->chatbotUri.'/api/ele-source-code']);
            $response = Http::post($this->chatbotUri.'/api/ele-source-code',['plan'=>$plan,'state'=>$state]);
        Log::info('gas_source_code',[$response->status()]);
            if($response->status() == 200)
            {
                return json_decode($response->body())->source_code;
            }
        } catch (\Exception $e)
        {
            Log::info($e->getMessage(),[]);
            return  '';
        }

        return  '';

    }

    private function getGasSourceCode($plan, $state)
    {
//        Log::info($plan, [$plan]);
        $plan = ConnectionApplication::PLAN_TYPE_REVERSE_MAPPER[$plan];
        $state = $this->stateMap($state);
        try {
            Log::info('url', [$this->chatbotUri.'/api/gas-source-code']);
            $response = Http::post($this->chatbotUri.'/api/gas-source-code',['plan'=>$plan,'state'=>$state]);
            Log::info('ele_source_code',[$response->status()]);

            if($response->status() == 200)
            {
                return json_decode($response->body())->source_code;

            }

        } catch (\Exception $e)
        {
            Log::info($e->getMessage(),[]);
            return  '';
        }
        return  '';


    }

    private function getBuyBackRate($solar, $state)
    {
        $state = $this->stateMap($state);
        try{
            $response = Http::post($this->chatbotUri.'/api/tariff-code',['solar'=>$solar == ConnectionApplication::HAS_SOLAR?'solar':'','state'=>$state]);
            Log::info('get_buy_pack_url',[$response->status()]);
            if($response->status() == 200)
            {
                return json_decode($response->body())->buypack_rate;

            }
        } catch (\Exception $e)
        {
            return  '';
        }
        return  '';



    }
    private function stateMap($state)
    {
        $stateList = ['New South Wales'=>'NSW','Victoria'=>'VIC','Queensland'=>'QLD',
            'South Australia'=>'SA','Northern Territory'=>'NT','TAS'=>'Tasmania','ACT'=>'Australian Capital Territory'];
        if(array_key_exists($state, $stateList))
        {
            return $stateList[$state];
        }
        return $state;

    }

    private function timeFrame(?string $str): string
    {
        if (!$str) {
            return '';
        }
        $res = preg_replace('/[^0-9.]+/', '', explode(':', $str)[0]);
        return strlen($res) > 1 ? '#' . $res . '00#' : '#0' . $res . '00#';
    }




}
