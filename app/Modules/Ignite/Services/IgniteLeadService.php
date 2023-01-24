<?php

namespace Ignite\Services;

use App\Events\Agency\CreateApplicationEvent;
use App\Events\NotifyAgentAfterLeadCreation;
use App\Services\Address\GBGAddressCleanse;
use App\Services\Address\GBGAddressMapper;
use App\Services\Helpers\Terminal;
use Ignite\Models\IgniteLead;
use App\Models\Agency;
use App\Models\Identification;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\AgentProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Services\NotifyBadAgentMailService;

use Locale;


class IgniteLeadService
{
    /**
     * @var mixed|null
     */
    private IgniteLead $lead;
    private ConnectionApplication $connectionApplication;
    private Identification $identification;

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

    const MAP_COUNTRY = [
        'au' => 'AUS',
        'th' => 'THA',
    ];

    const MAP_TYPE = [
        'PASSPORT' => 1,
        'DRIVER_LICENCE' => 2
    ];

    const MAP_IDENTITY_TYPE = [
        'DRIVER_LICENCE' => Identification::TYPE_DRIVING_LICENCE,
        'PASSPORT' => Identification::TYPE_PASSPORT,
    ];

    /**
     * Set attribute for create.
     *
     * @param  array  $leadInfo
     * @return void
     */
    private function setAttributeToApplication(array $leadInfo) : void{

        //tenant
        $this->connectionApplication->first_name   = $leadInfo['tenant']['firstName'] ?? 'Iron';
        $this->connectionApplication->last_name    = $leadInfo['tenant']['lastName'] ?? 'Man' ;
        $this->connectionApplication->source       = ConnectionApplication::SOURCE_IGNITE ;
        $this->connectionApplication->email        = $leadInfo['tenant']['email'] ?? 'abc@hood.ai';
        $this->connectionApplication->phone        = $leadInfo['tenant']['mobilePhoneNumber'] ?? '';
        $this->connectionApplication->dob          = date("Y-m-d", strtotime($leadInfo['tenant']['birthDate'])) ?? '1991/08/09';
        $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_TYPE_RENTER;

        //property
        $this->connectionApplication->moving_date    = date("Y-m-d", strtotime(  $leadInfo['property']['moveInDate'] ))  ?? '2021/10/02';


        $this->connectionApplication->created_by = AgentProfile::whereHas(
            'user',
            fn(Builder $user) => $user->where('email', $this->lead->agent_email)
        )->first()?->id ?? null;
    }



    private function setIdentification(array $leadInfo) : void
    {
        if ($this->connectionApplication->id && $leadInfo['tenant']['identityDocument'] !== null)
        {
            $this->identification = new Identification;

            $this->identification->connection_application_id = $this->connectionApplication->id;
            $this->identification->type = self::MAP_TYPE[$leadInfo['tenant']['identityDocument']['documentType']] ?? null;

            if ($leadInfo['tenant']['identityDocument']['documentType'] === 'PASSPORT') {
                $this->identification->card_number = $leadInfo['tenant']['identityDocument']['passportNumber'];
                $this->identification->country = self::MAP_COUNTRY[strtolower($leadInfo['tenant']['identityDocument']['passportCountryCode'])] ?? null;
            } else {
                $this->identification->card_number = $leadInfo['tenant']['identityDocument']['licenceNumber'];
                $this->identification->state = self::MAP_STATE[strtolower($leadInfo['tenant']['identityDocument']['licenceState'])] ?? null;
                $this->identification->expire_date = $leadInfo['tenant']['identityDocument']['licenceExpiryDate'];
            }

            $this->identification->save();
        }
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
    private function setOfficeAndAgencyId(array $leadInfo) : void{
        try {
            if ($this->connectionApplication->createdBy()->exists()){
                $agentProfile = $this->connectionApplication->createdBy;
                $agentAgencyId = $agentProfile->agency_id;
                $agentOfficeId = $agentProfile->office_id;

                $this->connectionApplication->agency_id = $agentAgencyId;
                $this->connectionApplication->office_id = $agentOfficeId;
            }
            else{
                $agencyName = $leadInfo['agency']['name'];
                $agency = Agency::where('name' , $agencyName)->first();
                if (!$agency) {
                    $agency = Agency::where('name', "Ignite-Hood-Agency")->first();
                }
                $this->connectionApplication->agency_id = $agency?->id ?? 1;
                $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
                if(!$agency) throw new Exception('Please run FoxieSeeder');
            }
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
    private function insertLead(array $leadInfo, ?array $cleanseAddress = null): bool
    {
        try {
            $this->connectionApplication = new ConnectionApplication;
            $this->connectionApplication->fill(GBGAddressMapper::toAppAddress($cleanseAddress));

            $this->lead = new IgniteLead();

            $this->setAttributeToIgniteLead($leadInfo);
            $this->lead->save();

            $this->setAttributeToApplication($leadInfo);
            $this->setOfficeAndAgencyId($leadInfo);

            $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;

            if ($this->connectionApplication->save()){
                $this->setIdentificationNew($leadInfo);
            }

            // $this->setIdentification($leadInfo);

            NotifyBadAgentMailService::check(
                $this->connectionApplication,
                'Ignite',
                $this->connectionApplication->agency->name ?? ($this->lead->agency_name ?? ''),
                $this->connectionApplication->office->name ?? 'Ignite-Hood-Office',
                $this->lead->agent_email ?? '',
            );

            $this->setServiceTypeTable($leadInfo['utilityConnectionsAllowed'] ?? ['water']);

           $this->lead->connection_application_id = $this->connectionApplication->id;
           $this->lead->save();

            // hubspot api call for creation
            NotifyAgentAfterLeadCreation::dispatch($this->connectionApplication->id);

            CreateApplicationEvent::dispatch($this->connectionApplication->id);

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
    public function create() : bool
    {
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
     * Filtering new only
     *
     * @param $leads
     *
     * @return array
     */
    private function getNewLeadOnly($leads): array
    {
        $this->logText("all: " . count($leads));
        $ids = [];
        foreach ($leads as $lead) {
            $id = $lead['application']['id'] ?? null;
            if ($id) {
                $ids[] = $id;
            }
        }

        $oldLeads = IgniteLead::query()
            ->select('lead_id')
            ->whereIn('lead_id', $ids)
            ->get()
            ->pluck('lead_id')
            ->toArray();

        $newLeads = [];

        foreach ($leads as $lead) {
            $id = $lead['application']['id'] ?? null;
            if (!in_array($id, $oldLeads)) {
                $newLeads[] = $lead;
            }
        }
       $this->logText("new: " . count($newLeads));
        return $newLeads;
    }

    /**
     *  Create dummy new ConnectionApplication via read json from path 'Storage/app/ignite_lead_response.json'
     *
     * @return void
     * @throws Exception
     */
    public function dummyCreate(): void
    {
        try {
            $leads = json_decode(file_get_contents(storage_path('app/ignite_lead_response.json')), true);

            $leads = $this->getNewLeadOnly($leads);
            if (sizeof($leads) > 0) {
                $cleanseAddress = $this->addressCleanse($leads);
            }

            foreach ($leads as $key =>  $leadInfo) {
                $this->insertLead($leadInfo, $cleanseAddress[$key] ?? null);
            };
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * Create new ConnectionApplication, run a loop.
     *
     * @param array $allLead
     * @param IgniteConnectionLeadService $service
     * @throws Exception
     */
    private function verifyData(array $allLead, IgniteConnectionLeadService $service)
    {
        $this->logText("New page");
        $allLead = $this->getNewLeadOnly($allLead);
        if (sizeof($allLead) > 0) {
            $cleanseAddress = $this->addressCleanse($allLead);
        }

        foreach ($allLead as $key => $leadInfo) {
            try {
                $address = $cleanseAddress[$key] ?? null;
                $this->insertLead($leadInfo, $address);
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
        };

        //TODO logic might be changed according to requirementes
        $nextPage = $service->getNextPageUrl();
        if ($nextPage !== '') {
            $allLead = $service->getIgniteLeads($service->getToken(), $nextPage);
            $this->verifyData($allLead, $service);
        }
    }

    /**
     * Applying GBG address cleanse
     *
     * @param array $leads
     *
     * @return array
     */
    private function addressCleanse(array $leads): array
    {
        $addresses = [];
        foreach ($leads as $lead) {
            $street = $lead['property']['street'] ?? "";
            $city = $lead['property']['suburb'] ?? "";
            $state = $lead['property']['state'] ?? "";
            $postcode = $lead['property']['postcode'] ?? "";

            $address = "$street, $city $state $postcode, Australia";

            $addresses[] = ['fullAddress' => $address];
        }
        return (new GBGAddressCleanse())->run($addresses);
    }

    private function setAttributeToIgniteLead(array $leadInfo)
    {
        $this->lead->all_fields_dump = json_encode($leadInfo);
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
        $this->lead->connectionProviderName = $leadInfo['connectionProviderName'] ?? '';
    }

    private function setIdentificationNew (array $leadInfo)
    {
        try {
            if (empty($leadInfo['tenant']['identityDocument'])) {
                throw new \Exception('Ignite Lead does not include identityDocument property');
            }

            $identityInfo = $leadInfo['tenant']['identityDocument'];

            if (!array_key_exists($identityInfo['documentType'], self::MAP_IDENTITY_TYPE)){
                throw new \Exception(sprintf('Ignite Lead identity type "%s" not valid', $identityInfo['documentType']));
            }

            $newIdentification = new Identification();
            $newIdentification->connection_application_id = $this->connectionApplication->id;
            $newIdentification->type = self::MAP_IDENTITY_TYPE[$identityInfo['documentType']];
            $newIdentification->card_number = $identityInfo['licenceNumber'] ?? ($identityInfo['passportNumber'] ?? null);
            $newIdentification->state  = self::MAP_STATE[strtolower( $identityInfo['licenceState'] )] ?? null;
            $newIdentification->expire_date = $identityInfo['licenceExpiryDate'] ?? ($identityInfo['passportExpiryDate'] ?? null);
            if (!empty($identityInfo['passportCountryCode'])) $newIdentification->country = Locale::getDisplayRegion(sprintf('-%s', $identityInfo['passportCountryCode']));

            $newIdentification->save();

        } catch (\Exception $e){
            \Log::error('Ignite Lead save identification failed', [
                'message' => $e->getMessage(),
                'leadInfo' => $leadInfo,
            ]);
        }
    }

    private function logText(string $text): void
    {
        Terminal::info($text);
        Log::info($text);
    }

}
