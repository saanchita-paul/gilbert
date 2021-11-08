<?php

namespace Ignite\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Agency;
use Ignite\Models\IgniteLead;
use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;

class IgniteLeadService
{
    /**
     * @var mixed|null
     */
    private IgniteLead $lead;
    private ConnectionApplication $connectionApplication;

    const TYPE_CREATE = 1;
    const TYPE_UPDATE = 2;

    const SERVICE_TYPE_POWER = 'power';
    const SERVICE_TYPE_GAS = 'gas';

    const TYPE_SERVICE = [
        'gas' => 'gas',
        'electricity' => 'power',
    ];

    const MAP_STATE_NSW = 'New South Wales';
    const MAP_STATE_VIC = 'Victoria';
    const MAP_STATE_QLD = 'Queensland';
    const MAP_STATE_SA = 'South Australia';
    const MAP_STATE_NT = 'Northern Territory';
    const MAP_STATE_TAS = 'Tasmania';
    const MAP_STATE_ACT = 'Australian Capital Territory';

    const MAP_STATE = [
        'nsw' => self::MAP_STATE_NSW,
        'vic' => self::MAP_STATE_VIC,
        'qld' => self::MAP_STATE_QLD,
        'sa' => self::MAP_STATE_SA,
        'nt' => self::MAP_STATE_NT,
        'tas' => self::MAP_STATE_TAS,
        'act' => self::MAP_STATE_ACT,
    ];

    /**
     * Set attribute for create.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function setAttribute($leadInfo){

        //tenant
        $this->connectionApplication->first_name = $leadInfo['tenant']['firstName'] ?? 'Iron';
        $this->connectionApplication->last_name = $leadInfo['tenant']['lastName'] ?? 'Man' ;
        $this->connectionApplication->source = ConnectionApplication::SOURCE_IGNITE ;
        $this->connectionApplication->email = $leadInfo['tenant']['email'] ?? 'abc@hood.ai';
        $this->connectionApplication->phone = $leadInfo['tenant']['mobilePhoneNumber'] ?? '';
        $this->connectionApplication->dob = date("Y-m-d", strtotime($leadInfo['tenant']['birthDate'])) ?? '1991/08/09';
        
        //property
        $this->connectionApplication->street_address = $leadInfo['property']['street'] ?? 'Queen Street';
        $this->connectionApplication->state = self::MAP_STATE[strtolower( $leadInfo['property']['state'] )] ?? '';
        $this->connectionApplication->postcode = $leadInfo['property']['postCode'] ?? '4000';
        $this->connectionApplication->moving_date = date("Y-m-d", strtotime(  $leadInfo['property']['moveInDate'] ))  ?? '2021/10/02';
        $this->connectionApplication->city = $leadInfo['property']['suburb'] ?? 'Brisbane City';

        //InginteLeads Table
        $this->lead->lead_id = $leadInfo['application']['id'] ?? '';
        $this->lead->approvedAt = Carbon::parse($leadInfo['application']['approvedAt'])->format("Y-m-d H:i:s") ?? '';

        //agency
        $this->lead->agency_id = $leadInfo['agency']['reaId'] ?? '';
        $this->lead->agency_name = $leadInfo['agency']['name'] ?? '';
        
        //agent
        $this->lead->agent_id = $leadInfo['agent']['id'] ?? '';
        $this->lead->agent_name = $leadInfo['agent']['name'] ?? '';
        $this->lead->agent_email = $leadInfo['agent']['email'] ?? '';
        
        //
        $this->lead->connectionProviderName = $leadInfo['connectionProviderName'] ?? '';
    }


    /**
     * Set office and agency id for connection_application table.
     *
     * @return void
     * @throws Exception
     */
    private function setOfficeAndAgencyId(){
        try {
            $agency = Agency::where('name' , "Ignite-Hood-Agency")->first();
            $this->connectionApplication->agency_id = $agency?->id ?? 1;
            $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
            if(!$agency) throw new Exception('Please run FoxieSeeder');
        } catch (\Throwable $th) {
            Log::error("Please run IgniteSeeder , php artisan db:seed --class=IgniteSeeder");
        }
    }

    /**
     * Create new ConnectionApplication one by one.
     *
     * @param  object  $leadInfo
     * @return bool
     * @throws Exception
     */
    private function insertLead($leadInfo): bool
    {
        try {
            $this->connectionApplication = new ConnectionApplication;
            $this->lead = new IgniteLead();
            
            $this->setOfficeAndAgencyId();

            $this->setAttribute($leadInfo);
    
            $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;
            $this->connectionApplication->save();
    
            $this->lead->all_fields_dump = json_encode($leadInfo);
            $this->lead->connection_application_id = $this->connectionApplication->id;
            $this->lead->save();
    
            return true;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error('inside create application block');
        }
        return true;

    }

    /**
     * Create new ConnectionApplication, run a loop.
     *
     * @param  array  $allLead
     * @return bool
     * @throws Exception
     */
    public function create($allLead){
        foreach ($allLead  as $leadInfo) {
            try {
                $igniteLead =  IgniteLead::where('lead_id' ,  $leadInfo['application']['id'])->first();
                if(!$igniteLead){
                    $this->insertLead($leadInfo);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error('inside create application loop');
                \Log::info( $leadInfo['application']['id'] . ' lead id already exists');
            }
        };
        return true;
    
    }
}
