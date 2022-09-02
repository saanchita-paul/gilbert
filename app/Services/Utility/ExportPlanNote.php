<?php


namespace App\Services\Utility;


use App\Models\ApplicationNote;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportPlanNote
{
    private $planDetails;
    private $connectionDetails;
    private array $mappedCSVData = [];
    private $noteData;

    public function __construct(private  int $id)
    {
        $this->fetchData()->mapData();
    }

    private function fetchData()
    {
        $this->noteData = ApplicationNote::find($this->id);
        $this->connectionDetails = json_decode($this->noteData?->connection_details, true);
        $this->planDetails = json_decode($this->noteData?->plan_details, true);
        return $this;
    }

    private function mapData()
    {
        try {
            $mappedConnData = $this->mapConnectionApp($this->connectionDetails);
            $mappedPlanData = $this->mapPlanDetails($this->planDetails, $this->noteData->type);
            $this->mappedCSVData = array_merge($mappedConnData, $mappedPlanData);
        }catch ( \Exception $exception)
        {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }


    }

    private function mapConnectionApp($data)
    {
        return [
            'First Name' => data_get($data, 'first_name', ''),
            'Last Name' => data_get($data, 'last_name', ''),
            'Utility Type' => data_get($data, 'utility_type', ''),
            'Connection Address' => data_get($data, 'address_text', ''),
            'Connection Data' => data_get($data, 'moving_date', ''),
            'Supplier' => data_get($data, 'supplier', ''),
        ];
    }

    private function mapPlanDetails($data, $submitType)
    {
        if($submitType == ApplicationNote::SUBMITTED_CONNECTION){

            $mappdata = [
                'Plan Name' => data_get($data, 'name', ''),
                'Plan Description' => data_get($data, 'description', ''),
                'Customer type' => data_get($data, 'customer_type', ''),
                'Ele Distributor' => data_get($data, 'distributor_name.electricity', ''),
                'Gas Distributor' => data_get($data, 'distributor_name.gas', ''),
                'Disclaimer' => data_get($data, 'disclaimer', ''),
                'State Disclaimer' => data_get($data, 'state_disclaimer', ''),
                'Ele Connection Fee' => data_get($data, 'connection_fees.electricity', ''),
                'Gas Connection Fee' => data_get($data, 'connection_fees.gas', ''),
                'Promotion Title' => data_get($data, 'promotion_title', ''),
                'Promotion Title Header' => data_get($data, 'promotion.header', ''),
                'Promotion Lines' => join(' ', data_get($data, 'promotion.lines', [])),
    
    
                'Ele Daily Supply Charge Rates Title' => data_get($data, 'rates.electricity.daily_supply_charge.title', ''),
                'Ele Daily Supply Charge Rates (BD)' => data_get($data, 'rates.electricity.daily_supply_charge.before_discount', ''),
                'Ele Daily Supply Charge Rates (AD)' => data_get($data, 'rates.electricity.daily_supply_charge.after_discount', ''),
                'Ele Usage Rates Per Day Rates Title' => data_get($data, 'rates.electricity.usage_rates.peak_usage_per_day.title', ''),
                'Ele Usage Rates Per Day Rates (BD)' => data_get($data, 'rates.electricity.usage_rates.peak_usage_per_day.before_discount', ''),
                'Ele Usage Rates Per Day Rates  (AD)' => data_get($data, 'rates.electricity.usage_rates.peak_usage_per_day.after_discount', ''),
    
                'Plan Details Benefit Period' => data_get($data, 'plan_details.benefit_period', ''),
                'Plan Details Exit Fees' => data_get($data, 'plan_details.exit_fees', ''),
                'Plan Details Connection Fees' => data_get($data, 'plan_details.connection_fees', ''),
                'Plan Details Rates' => data_get($data, 'plan_details.rates', ''),
                'Plan Details Late Payment Fee' => data_get($data, 'plan_details.late_payment_fee', ''),
                'Plan Details Customer Type' => data_get($data, 'plan_details.customer_type', ''),
    
                'Ele Discount' => data_get($data, 'discounts.electricity', ''),
                'Gas Discount' => data_get($data, 'discounts.gas', ''),
    
                'Ele State Distributor Discount' => data_get($data, 'state_distributor_discount.electricity', ''),
                'Gas State Distributor Discount' => data_get($data, 'state_distributor_discount.gas', ''),
    
                'Solar BuyBack Single Rate c_kwh' => data_get($data, 'solar_buy_pack_rate.single_rate_c_kwh', ''),
                'Solar BuyBack Peak c_kwh' => data_get($data, 'solar_buy_pack_rate.peak_c_kwh', ''),
                'Solar BuyBack Shoulder Rate c_kwh' => data_get($data, 'solar_buy_pack_rate.shoulder_c_kwh', ''),
                'Solar BuyBack Off Peak Rate c_kwh' => data_get($data, 'solar_buy_pack_rate.off_peak_c_kwh',''),
    
            ];
    
            $gasUsageRate = data_get($data, 'rates.gas.usage_rates', []);
            $mappedGasRateData = $this->mapGasUsageRate($gasUsageRate);
            $mappedFeatureData = $this->mapFeature(data_get($data, 'features', []));
            return array_merge($mappdata, $mappedGasRateData, $mappedFeatureData);
        }

        if($submitType == ApplicationNote::SUBMITTED_ORIGIN){
            $mappData = [
                'Rates' => data_get($data, 'rates', ''),
                'Exit Fees' => data_get($data, 'exit_fees', ''),
                'Benefit Period' => data_get($data, 'benefit_period'),
            ];

            if(!empty($data['plans']['electricity'])){
                $mapElecData = [
                    'Ele Plan Name' => 'Origin Home Assist', // static
                    'Ele Plan Description' => $data['plans']['electricity']['offers'] ? $data['plans']['electricity']['offers']['line_1'] . '. ' . $data['plans']['electricity']['offers']['line_1'] : '',
                    'Ele Distributor' => $data['plans']['electricity']['distributor_name'] ?? '',
                    'Ele Connection Fee Per Year' => $data['plans']['electricity']['vdo']['vdo_dmo_amount'] ?? '',
                    'Ele Discount Rate' => $data['plans']['electricity']['vdo']['vdo_dmo_percentage'] ?? '',
    
                    'Standard Connection Fee' => data_get($data, 'plans.electricity.fees.standard_connection_fee', ''),
                    'Same Day Connection Fee' => data_get($data, 'plans.electricity.fees.same_day_connection_fee', ''),
                ];

                for($i=0; $i<count($data['plans']['electricity']['supply_charge']); $i++){
                    $supply = $data['plans']['electricity']['supply_charge'][$i];

                    $mapElecData['Ele Supply Charge #' . $i . ' Description'] = $supply['description'] ?? '';
                    $mapElecData['Ele Supply Charge #' . $i . ' Unit' ] = $supply['unit'] ?? '';
                    $mapElecData['Ele Supply Charge #' . $i . ' Total' ] = $supply['gst_inc'] ?? '';
                }

                for($i=0; $i<count($data['plans']['electricity']['usage_charge']); $i++){
                    $supply = $data['plans']['electricity']['usage_charge'][$i];

                    $mapElecData['Ele Usage Charge #' . $i . ' Description'] = $supply['description'] ?? '';
                    $mapElecData['Ele Usage Charge #' . $i . ' Unit' ] = $supply['unit'] ?? '';
                    $mapElecData['Ele Usage Charge #' . $i . ' Total' ] = $supply['gst_inc'] ?? '';
                }

                for($i=0; $i<count($data['plans']['electricity']['bpid_links']); $i++){
                    $link = $data['plans']['electricity']['bpid_links'][$i];
                    $mapElecData['Ele Link #' . $i . ' Description'] = $link['offer_name'];
                    $mapElecData['Ele Link #' . $i . ' URL'] = $link['file_url'];
                }

                $mappData = array_merge($mappData, $mapElecData);
            }
            if(!empty($data['plans']['gas'])){
                $mapGasData = [
                    'Gas Plan Name' => 'Origin Advantage Variable', // static
                    // 'Plan Description' => $data['plans']['electricity']['offers'] ? $data['plans']['electricity']['offers']['line_1'] . '. ' . $data['plans']['electricity']['offers']['line_1'] : '',
                    // 'Gas Distributor' => $data['plans']['electricity']['distributor_name'] ?? '',
                    'Gas Standard Connection Fee' => data_get($data, 'plans.gas.fees.standard_connection_fee', ''),
                ];

                for($i=0; $i<count($data['plans']['gas']['supply_charge']); $i++){
                    $supply = $data['plans']['gas']['supply_charge'][$i];

                    $mapGasData['Gas Supply Charge #' . $i . ' Description'] = $supply['description'] ?? '';
                    $mapGasData['Gas Supply Charge #' . $i . ' Unit' ] = $supply['unit'] ?? '';
                    $mapGasData['Gas Supply Charge #' . $i . ' Total' ] = $supply['gst_inc'] ?? '';
                }

                for($i=0; $i<count($data['plans']['gas']['usage_charge']); $i++){
                    $supply = $data['plans']['gas']['usage_charge'][$i];

                    $mapGasData['Gas Usage Charge #' . $i . ' Description'] = $supply['description'] ?? '';
                    $mapGasData['Gas Usage Charge #' . $i . ' Unit' ] = $supply['unit'] ?? '';
                    $mapGasData['Gas Usage Charge #' . $i . ' Total' ] = $supply['gst_inc'] ?? '';
                }

                for($i=0; $i<count($data['plans']['gas']['bpid_links']); $i++){
                    $link = $data['plans']['gas']['bpid_links'][$i];
                    $mapGasData['Gas Link #' . $i . ' Description'] = $link['offer_name'];
                    $mapGasData['Gas Link #' . $i . ' URL'] = $link['file_url'];
                }

                $mappData = array_merge($mappData, $mapGasData);
            }
    
            return $mappData;
        }

        if($submitType == ApplicationNote::SUBMITTED_POWERSHOP){
            $mappData = [];

            if(!empty($data['plans']['electricity'])){
                $elecData = [
                    'Elec Plan Name' => '',
                    'Elec Plan Description' => '',
                    'Elec Distributor' => '',
                    'Elec Connection Fee Per Year' => '',
                    'Elec Discount Rate' => '',
                    'Elec Consumption' => '',
                    'Elec Daily Supply Charge' => '',
                    'Elec Single Rate Tariff' => '',
                    'Elec Manual Connection' => '',
                    'Elec Remote Connection' => '',
                    'Solar Feed Rate' => '',
                ];

                $mappData = array_merge($mappData, $elecData);
            }

            if(!empty($data['plans']['gas'])){
                $gasData = [
                    'Gas Daily Supply Charge' => '',
                    'Gas Anytime Charge' => '',
                    'Gas Reconnetion Charge' => '',
                ];

                $mappData = array_merge($mappData, $gasData);
            }

            return $mappData;
        }

    }


    private function mapFeature($features = [])
    {
        $mappedFeature = [];
        foreach ($features as $key=>$feature) {
            $mappedFeature['Feature_'. ($key + 1). ' Title'] = data_get($feature, 'title', '');
            $mappedFeature['Feature_'. ($key + 1). ' Description'] = data_get($feature, 'description', '');
        }
        return $mappedFeature;

    }



    private function mapGasUsageRate($userRates)
    {


        $plan = data_get($this->connectionDetails, 'plan_type');
        $rates = [];
        //for total plan and basic plan this data is array but nofrill plan this is object
        if($plan !== 'no_frills')
        {
            foreach ($userRates as $key=>$data)
            {
                $session = '(Winter)';
                if($key === 1) {
                    $session = '(Non-Winter)';
                }
                $rate = [
                    $session . 'Gas Usage Rates Title' => data_get($data, 'session_description', ''),
                    $session .  'Gas Daily Supply Charge Rates Title' => data_get($data, 'data.daily_supply_charge.title', ''),
                    $session .  'Gas Daily Supply Charge Rates (BD)' => data_get($data, 'data.daily_supply_charge.before_discount', ''),
                    $session .  'Gas Daily Supply Charge Rates (AD)' => data_get($data, 'data.daily_supply_charge.after_discount', ''),

                    $session .  'Gas First Rates Title' => data_get($data, 'data.first.title', ''),
                    $session .  'Gas First Rates (BD)' => data_get($data, 'data.first.before_discount', ''),
                    $session .  'Gas First Rates (AD)' => data_get($data, 'data.first.after_discount', ''),


                    $session .  'Gas Second Rates Title' => data_get($data, 'data.second.title', ''),
                    $session .   'Gas Second Rates (BD)' => data_get($data, 'data.second.before_discount', ''),
                    $session .  'Gas Second Rates (AD)' => data_get($data, 'data.second.after_discount', ''),


                    $session .  'Gas Third Rates Title' => data_get($data, 'data.third.title', ''),
                    $session . 'Gas Third Rates (BD)' => data_get($data, 'data.third.before_discount', ''),
                    $session . 'Gas Third Rates (AD)' => data_get($data, 'data.third.after_discount', ''),

                    $session .  'Gas Fourth Rates Title' => data_get($data, 'data.fourth.title', ''),
                    $session .  'Gas Fourth Rates (BD)' => data_get($data, 'data.fourth.before_discount', ''),
                    $session .  'Gas Fourth Rates (AD)' => data_get($data, 'data.fourth.after_discount', ''),

                    $session .  'Gas Fifth Rates Title' => data_get($data, 'data.fifth.title', ''),
                    $session .  'Gas Fifth Rates (BD)' => data_get($data, 'data.fifth.before_discount', ''),
                    $session .   'Gas Fifth Rates (AD)' => data_get($data, 'data.fifth.after_discount', ''),


                    $session .  'Gas Balance Rates Title' => data_get($data, 'data.balance.title', ''),
                    $session . 'Gas Balance Rates (BD)' => data_get($data, 'data.balance.before_discount', ''),
                    $session . 'Gas Balance Rates (AD)' => data_get($data, 'data.balance.after_discount', ''),
                ];

                $rates = array_merge($rates, $rate);
            }
        } else {
            $rates = [
               'GAS Daily Supply Charge Rates Title' => data_get($userRates, 'daily_supply_charge.title', ''),
               'GAS Daily Supply Charge Rates (BD)' => data_get($userRates, 'daily_supply_charge.before_discount', ''),
               'GAS Daily Supply Charge Rates (AD)' => data_get($userRates, 'daily_supply_charge.after_discount', ''),
               'GAS Usage Rates Per Day Rates Title' => data_get($userRates, 'peak_usage_per_day.title', ''),
               'GAS Usage Rates Per Day Rates (BD)' => data_get($userRates, 'peak_usage_per_day.before_discount', ''),
               'GAS Usage Rates Per Day Rates  (AD)' => data_get($userRates, 'peak_usage_per_day.after_discount', ''),
            ];
        }


        return $rates;
    }

    private function getCsvName()
    {

        $applicationId = data_get($this->connectionDetails, 'application_id', '1');
        $plan = data_get($this->planDetails, 'name', );
        $retailer = data_get($this->connectionDetails, 'supplier', 'EA');
        $createData = Carbon::parse($this->noteData?->created_at)->format("Y_m_d_H_i");
        return 'Lead_'.$applicationId. '_'. $plan . '_'. $retailer. '_' . $createData.'.csv';
    }

    public function run()
    {
        try {
            return (new FastExcel(collect([ $this->mappedCSVData ])))->download($this->getCsvName());
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }



    }

}
