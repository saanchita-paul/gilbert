<?php

namespace Ignite\Services;

use App\Events\NotifyAgentAfterLeadCreation;
use App\Jobs\CreateHubspotProperty;
use Exception;
use Carbon\Carbon;
use App\Models\Agency;
use Ignite\Models\IgniteLead;
use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\AgentProfile;
use Illuminate\Database\Eloquent\Builder;
use App\Services\NotifyBadAgentMailService;

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
    const SERVICE_TYPE_GAS   = 'gas';

    const TYPE_SERVICE = [
        'gas'         => 'gas',
        'electricity' => 'power',
    ];

    const MAP_STATE_NSW = 'New South Wales';
    const MAP_STATE_VIC = 'Victoria';
    const MAP_STATE_QLD = 'Queensland';
    const MAP_STATE_SA  = 'South Australia';
    const MAP_STATE_NT  = 'Northern Territory';
    const MAP_STATE_TAS = 'Tasmania';
    const MAP_STATE_ACT = 'Australian Capital Territory';
    const MAP_STATE_WA  = 'Western Australia';

    const MAP_STATE = [
        'nsw' => self::MAP_STATE_NSW,
        'vic' => self::MAP_STATE_VIC,
        'qld' => self::MAP_STATE_QLD,
        'sa'  => self::MAP_STATE_SA,
        'nt'  => self::MAP_STATE_NT,
        'tas' => self::MAP_STATE_TAS,
        'act' => self::MAP_STATE_ACT,
        'wa'  => self::MAP_STATE_WA,
    ];

    /**
     * Set attribute for create.
     *
     * @param  array  $leadInfo
     * @return void
     */
    private function setAttribute(array $leadInfo) : void{

        //tenant
        $this->connectionApplication->first_name   = $leadInfo['tenant']['firstName'] ?? 'Iron';
        $this->connectionApplication->last_name    = $leadInfo['tenant']['lastName'] ?? 'Man' ;
        $this->connectionApplication->source       = ConnectionApplication::SOURCE_IGNITE ;
        $this->connectionApplication->email        = $leadInfo['tenant']['email'] ?? 'abc@hood.ai';
        $this->connectionApplication->phone        = $leadInfo['tenant']['mobilePhoneNumber'] ?? '';
        $this->connectionApplication->dob          = date("Y-m-d", strtotime($leadInfo['tenant']['birthDate'])) ?? '1991/08/09';
        $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_TYPE_RENTER;

        //property
        $street   =  $leadInfo['property']['street'] ?? '';
        $state    = self::MAP_STATE[strtolower( $leadInfo['property']['state'] )] ?? '';
        $postcode = $leadInfo['property']['postcode'] ?? '';
        $city     = $leadInfo['property']['suburb'] ?? '';

        $this->connectionApplication->street_address = $street;
        $this->connectionApplication->state          = $state;
        $this->connectionApplication->postcode       = $postcode;
        $this->connectionApplication->city           = $city;
        $this->connectionApplication->moving_date    = date("Y-m-d", strtotime(  $leadInfo['property']['moveInDate'] ))  ?? '2021/10/02';

        $this->connectionApplication->address_text   = $street . ' ' . $city . ' ' . $state  . ' ' . $postcode;

        //InginteLeads Table
        $this->lead->lead_id     = $leadInfo['application']['id'] ?? '';
        $this->lead->approvedAt  = Carbon::parse($leadInfo['application']['approvedAt'])->format("Y-m-d H:i:s") ?? '';

        //agency
        $this->lead->agency_id   = $leadInfo['agency']['reaId'] ?? '';
        $this->lead->agency_name = $leadInfo['agency']['name'] ?? '';

        //agent
        $this->lead->agent_id    = $leadInfo['agents'][0]['id'] ?? '';
        $this->lead->agent_name  = $leadInfo['agents'][0]['name'] ?? '';
        $this->lead->agent_email = $leadInfo['agents'][0]['email'] ?? '';
        $this->connectionApplication->created_by = AgentProfile::whereHas(
            'user',
            fn(Builder $user) => $user->where('email', $this->lead->agent_email)
        )->first()?->id ?? null;

        //
        $this->lead->connectionProviderName = $leadInfo['connectionProviderName'] ?? '';
    }


    /**
     * Set service types.
     *
     * @param array $serviceTypes
     * @return void
     * @throws Exception
     */
    private function setServiceTypeTable($serviceTypes = []) : void{
        /**
         * follow docs for details implementation.
         *
         * ? https://partner.realestate.com.au/documentation/api/connection-leads-api/usage/
         */
        $services     = [] ;
        if(in_array( 'all' ,  $serviceTypes )){
            $services = [ 'gas' , 'power' , 'internet' , 'water' ] ;
        } else if($this->isStateVic($this->connectionApplication->state) && !in_array( 'all' ,  $serviceTypes )){
            $services = ['water'];
        } else if(in_array( 'water' ,  $serviceTypes )){
            $services = ['water'];
        }

        try {
            foreach ($services as $value) {
                info($value);
                $this->connectionApplication->connectionServices()->create(
                    [
                        'service_type' => $value ,
                        'status'       => ConnectionService::STATUS_EA_PROCESSINF,
                    ]
                );
            }
        } catch (\Exception $ex) {
                \Log::error('problem in service type table');
                \Log::error($ex->getMessage());
                \Log::error($ex->getTraceAsString());
        }
    }

    private function isStateVic($state) : bool
    {
        return strtolower($state) === 'vic' || strtolower($state) === 'victoria';
    }

    /**
     * Set office and agency id for connection_application table.
     *
     * @return void
     * @throws Exception
     */
    private function setOfficeAndAgencyId() : void{
        try {
            $agency = Agency::where('name' , "Ignite-Hood-Agency")->first();
            $this->connectionApplication->agency_id = $agency?->id ?? 1;
            $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
            if(!$agency) throw new Exception('Please run FoxieSeeder');
        } catch (\Exception $exception) {
            Log::error("Please run IgniteSeeder , php artisan db:seed --class=IgniteSeeder");
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    }

    /**
     * Create new ConnectionApplication one by one.
     *
     * @param  array  $leadInfo
     * @return bool
     * @throws Exception
     */
    private function insertLead(array $leadInfo): bool
    {
        try {
            $this->connectionApplication = new ConnectionApplication;
            $this->lead = new IgniteLead();

            $this->setOfficeAndAgencyId();

            $this->setAttribute($leadInfo);


            $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;
            $this->connectionApplication->save();

            NotifyBadAgentMailService::check(
                $this->connectionApplication,
                'Ignite',
                $this->lead->agency_name ?? '',
                Agency::where('name' , "Ignite-Hood-Agency")->first()?->offices[0]?->name ?? 'Ignite-Hood-Office',
                $this->lead->agent_email ?? '',
            );

            $this->setServiceTypeTable($leadInfo['utilityConnectionsAllowed'] ?? ['water']);

            $this->lead->all_fields_dump = json_encode($leadInfo);
            $this->lead->connection_application_id = $this->connectionApplication->id;
            $this->lead->save();



            // hubspot api call for creation
            NotifyAgentAfterLeadCreation::dispatch($this->lead->id);
            CreateHubspotProperty::dispatch($this->lead->id);

            return true;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            \Log::error('inside create application block');
        }
        return true;
    }

    /**
     * Create new ConnectionApplication, run a loop.
     *
     * @return bool
     * @throws Exception
     */
    public function create() : bool{
        try {
            $service = new IgniteConnectionLeadService();
            $token =  $service->authenticate();
            $leads =  $service->getIgniteLeads($token , $service->getConnctionLeadUrl());
            $this->verifyData($leads , $service);
            return true;
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * Create new ConnectionApplication, run a loop.
     *
     * @param  array  $allLead
     * @return bool
     * @throws Exception
     */
    private function verifyData(array $allLead , IgniteConnectionLeadService $service) : int{

        foreach ($allLead  as $leadInfo) {
            try {
                $igniteLead = IgniteLead::where('lead_id' ,  $leadInfo['application']['id'])->first();
                if(!$igniteLead){
                    $this->insertLead($leadInfo);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
        };

        //TODO logic might be changed according to requirementes
        if($service->getNextPageUrl() == '') {
        // if(count($allLead) < 25) {
            return 0;
        }else{
            $allLead =  $service->getIgniteLeads( $service->getToken() , $service->getNextPageUrl() );
            return $this->verifyData($allLead , $service);
        }

    }

}
