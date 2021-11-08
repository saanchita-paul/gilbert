<?php

namespace Foxie\Services;

use App\Modules\Foxie\Services\LeadStatusMapper;
use Exception;
use Carbon\Carbon;
use App\Models\Agency;
use Foxie\Models\SugerLead;
use Illuminate\Http\Request;
use App\Models\Identification;
use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;

class SugerLeadService
{
    /**
     * @var mixed|null
     */
    private SugerLead $lead;
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
    private function setAttribute(Request $request){
        $this->connectionApplication->first_name = $request->first_name ?? 'Iron';
        $this->connectionApplication->last_name = $request->last_name ?? 'Man' ;
        $this->connectionApplication->source = ConnectionApplication::SOURCE_FOXIE ;
        $this->connectionApplication->email = $request->email1 ?? 'abc@hood.ai';
        $this->connectionApplication->address_text = $request->full_address_c ?? '';
        $this->connectionApplication->dob = date("Y-m-d", strtotime($request->birthdate)) ?? '1991/08/09';
        $this->connectionApplication->moving_date = date("Y-m-d", strtotime($request->move_in_date_c))  ?? '2021/10/02';
        $this->connectionApplication->address_unit = $request->primary_address_unit_c ?? '';
        $this->connectionApplication->street_address = $request->primary_address_street ?? 'Queen Street';
        $this->connectionApplication->city = $request->primary_address_city ?? 'Brisbane City';
        $this->connectionApplication->postcode = $request->alt_address_postcode ?? '4000';
        $this->connectionApplication->phone = $request->phone_mobile ?? '';
        $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_MAPPING[$request->property_relationship_c] ?? null ;
        $this->connectionApplication->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[$request->customer_type_c] ?? null ;
        $this->connectionApplication->state = self::MAP_STATE[strtolower( $request->primary_address_state )] ?? '';
        $this->connectionApplication->country = $request->primary_address_country ?? 'Australia';
        $this->connectionApplication->nmi = $request->electricity_nmi_c ?? '';
        $this->connectionApplication->mirn = $request->gas_mirn_c ?? '';
        $this->connectionApplication->unit_number = $request->primary_address_unit_c ?? '';
        $this->connectionApplication->plan_type = $request->meter_plan_type_c ?? '3';
        // $this->connectionApplication->created_at = now();
        // $this->connectionApplication->title = $request->salutation ?? 'Mr';
        $this->connectionApplication->title = 'Mr';

        //SUGER LEADS TABLE
        $this->lead->service_address = $request->full_address_c ?? '';
        $this->lead->foxie_lead_source = $request->lead_source ?? '';
        $this->lead->foxie_lead_source_description = $request->lead_source_description ?? '';
        $this->lead->office_branch = $request->office_c ?? '';
        $this->lead->agent_name = $request->agent_c ?? '';
        $this->lead->agency_id = $request->foxie_agents_id_c ?? '';
        $this->lead->agency_name = $request->office_c ?? '';
        $this->lead->lead_id = $request->id_c ?? '';
        $this->lead->foxie_date_entered =  Carbon::parse($request->date_entered)->format("Y-m-d H:i:s")  ?? null;
        $this->lead->foxie_date_modified = Carbon::parse($request->date_modified)->format("Y-m-d H:i:s")  ?? null;
        $this->lead->created_at = now();
    }

    private function setIdentificationTable(Request $request , $type){
        //IDENTIFICATION TABLE
        $identification = '';
        isset($this->connectionApplication->identification ) ?
        $identification = $this->connectionApplication->identification :
        $identification = new Identification;

        try {
            $formattedDate = Carbon::parse($request->id_expiry_c)->format("Y-m-d");
            $mappedType = Identification::TYPE_MAP[$request->id_type_c];

            if($type == self::TYPE_CREATE){
                $identification->expire_date = $formattedDate ?? null;
                $identification->type = $mappedType ?? null ;
                $identification->card_number = $request->id_number_c ?? null ;
            }else{
                $request->id_type_c ? $identification->type = $mappedType ?? null : '';
                $request->id_expiry_c ? $identification->expire_date = $formattedDate : '';
                $request->id_number_c ? $identification->card_number = $request->id_number_c : '';
            }
            $identification->connection_application_id = $this->connectionApplication->id;
            $identification->save();

        } catch (\Exception $ex) {
                \Log::error('problem in identification table');
                \Log::error($ex->getMessage());
        }
    }

    private function setServiceTypeTable(Request $request , $type){
        // SERVICETYPE TABLE
        info('checking service_c1');
        if(!isset($request->service_c)) return;
        info('checking service_c');
        // expected format example Electricity_Gas
        $services = explode("_", strtolower($request->service_c));

        try {
            if($type == self::TYPE_UPDATE) $this->connectionApplication->connectionServices()->delete();
            foreach ($services as $value) {
                $this->connectionApplication->connectionServices()->create(
                    [
                        'service_type' => self::TYPE_SERVICE[ $value ] ,
                        'status' => ConnectionApplication::STATUS_UNASSIGNED ,
                    ]
                );
            }
        } catch (\Exception $ex) {
                \Log::error('problem in service type table');
                \Log::error($ex->getMessage());
        }
    }

    /**
     * Set attribute for update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function setAttributeUpdate(Request $request) : void {

        $request->first_name ? $this->connectionApplication->first_name = $request->first_name : '';
        $request->last_name ? $this->connectionApplication->last_name = $request->last_name : '';
        $request->email1 ? $this->connectionApplication->email = $request->email1 : '';
        $request->birthdate ? $this->connectionApplication->dob = date("Y-m-d", strtotime($request->birthdate)) : '';
        $request->move_in_date_c ? $this->connectionApplication->moving_date = date("Y-m-d", strtotime($request->move_in_date_c)) : '';
        $request->primary_address_unit_c ? $this->connectionApplication->address_unit = $request->primary_address_unit_c : '';
        $request->full_address_c ? $this->connectionApplication->address_text = $request->full_address_c : '';
        $request->primary_address_street ? $this->connectionApplication->street_address = $request->primary_address_street : '';
        $request->primary_address_city ? $this->connectionApplication->city = $request->primary_address_city : '';
        $request->alt_address_postcode ? $this->connectionApplication->postcode = $request->alt_address_postcode : '';
        $request->primary_address_state ? $this->connectionApplication->state = self::MAP_STATE[ strtolower( $request->primary_address_state ) ] : '';
        $request->primary_address_country ? $this->connectionApplication->country = $request->primary_address_country : '';
        $request->phone_mobile ? $this->connectionApplication->phone = $request->phone_mobile : '';
        $request->property_relationship_c ? $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_MAPPING[$request->property_relationship_c] ?? $this->connectionApplication->tenancy_type : '';

        $request->customer_type_c ? $this->connectionApplication->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[$request->customer_type_c] ?? $this->connectionApplication->property_type : '';

        $request->electricity_nmi_c ? $this->connectionApplication->nmi = $request->electricity_nmi_c : '';
        $request->gas_mirn_c ? $this->connectionApplication->mirn = $request->gas_mirn_c : '';
        $request->primary_address_unit_c ? $this->connectionApplication->unit_number = $request->primary_address_unit_c : '';
        $request->meter_plan_type_c ? $this->connectionApplication->plan_type = $request->meter_plan_type_c : '';
        // $this->connectionApplication->updated_at = now();


        //SUGER LEADS TABLE
        $request->full_address_c ? $this->lead->service_address = $request->full_address_c : '';
        $request->office_c ? $this->lead->office_branch = $request->office_c : '';
        $request->foxie_agents_id_c ? $this->lead->agency_id = $request->foxie_agents_id_c : '';
        $request->agent_c ? $this->lead->agent_name = $request->agent_c : '';
        $request->id_c ? $this->lead->lead_id = $request->id_c : '';
        $request->office_c ? $this->lead->agency_name = $request->office_c : '';
        $request->lead_source_description ? $this->lead->foxie_lead_source_description = $request->lead_source_description : '';
        $request->lead_source ? $this->lead->foxie_lead_source = $request->lead_source : '';
        $this->lead->foxie_date_modified = Carbon::parse($request->date_modified)->format("Y-m-d H:i:s") ?? null;
        $this->lead->updated_at = now();

    }

    private function setOfficeAndAgencyId(){
        try {
            $agency = Agency::where('name' , "Foxie-Hood-Agent")->first();
            $this->connectionApplication->agency_id = $agency?->id ?? 1;
            $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
            if(!$agency) throw new Exception('Please run FoxieSeeder');
        } catch (\Throwable $th) {
            Log::error("Please run FoxieSeeder , php artisan db:seed --class=FoxieSeeder");
        }
    }

    /**
     * Create new ConnectionApplication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ConnectionApplication $newApplication
     */
    public function create(Request $request): ConnectionApplication
    {
        $this->connectionApplication = new ConnectionApplication;
        $this->lead = new SugerLead();

        $this->setOfficeAndAgencyId();
        $this->setAttribute($request);

        $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;
        $this->connectionApplication->save();
        $this->lead->all_fields_dump = json_encode(request()->all());
        $this->lead->connection_application_id = $this->connectionApplication->id;
        $this->lead->save();

        //set identification table
        $this->setIdentificationTable($request , self::TYPE_CREATE);

        //set service type table
        $this->setServiceTypeTable($request , self::TYPE_CREATE ) ;

        return $this->connectionApplication;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return bool $successOrFailed
     */
    public function update(Request $request, $id): bool
    {
        try {
            $this->connectionApplication = ConnectionApplication::where('id' , $id)->where('source' , ConnectionApplication::SOURCE_FOXIE)->first();
            $this->lead = $this->connectionApplication->SugerLead;

            $this->setAttributeUpdate($request);

            $this->connectionApplication->save();
            $all_fields_dump =  json_decode($this->lead->all_fields_dump);
            $mergedUpdatedData =  collect($all_fields_dump)->merge($request->all());
            $this->lead->update(["all_fields_dump" => json_encode($mergedUpdatedData)]);

            //set connetion application
            $this->setIdentificationTable($request , self::TYPE_UPDATE );

            //set service type table
            $this->setServiceTypeTable($request , self::TYPE_UPDATE ) ;

            return true;
        } catch (\Exception $ex) {
            Log::error("Problem in updating");
            Log::error($ex->getMessage());
            throw new Exception("Error Processing Request", 1);
        }
    }

    /**
     * @throws Exception
     */
    public function findById($id)
    {
        $identificationColumns = 'identification:id,connection_application_id,expire_date,type';

        $lead  = ConnectionApplication::with([$identificationColumns])
            ->where( 'source' , ConnectionApplication::SOURCE_FOXIE )
            ->where('id' , $id)
            ->first();

        return !$lead ? throw new Exception("Error Processing Request", 1) : array_merge(
            $lead->toArray(),
            (new LeadStatusMapper($id))->toArray()
        );
    }

    /**
     * @throws Exception
     */
    public function get($from, $to): array
    {
        $identificationColumns = 'identification:id,connection_application_id,expire_date,type';
        $leads =  ConnectionApplication::with([$identificationColumns])
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->where('source' , ConnectionApplication::SOURCE_FOXIE )
            ->get();
        $data = [];
        foreach ($leads as $lead) {
            $data[] = array_merge(
                $lead->toArray(),
                (new LeadStatusMapper($lead->id))->toArray()
            );
        }

        return $data;
    }

    /**
     * Show the specified resource in storage.
     *
     * @param String|null $from
     * @param String|null $to
     * @param null $id
     * @return array $leads
     * @throws Exception
     */
    public function show(String $from = null , String $to = null , $id = null) : array
    {
        try {
            $leads = null;
            $connectionServiceColumns = 'connectionServices:id,connection_application_id,service_type,status';

            if($id == null){
                $lead =  ConnectionApplication::with([$identificationColumns])
                    ->where('created_at', '>=', $from)
                    ->where('created_at', '<=', $to)
                    ->where('source' , ConnectionApplication::SOURCE_FOXIE )
                    ->get();
            }else{
                $lead  = ConnectionApplication::with([$identificationColumns])
                    ->where( 'source' , ConnectionApplication::SOURCE_FOXIE )
                    ->where('id' , $id)
                    ->first();
            }



            return !$lead ? throw new Exception("Error Processing Request", 1) : array_merge(
                $lead->toArray(),
                (new LeadStatusMapper($id))->toArray()
            );

        } catch (\Exception $ex) {
                Log::error("Problem in retrieving data");
                Log::error($ex->getMessage());
                throw new Exception("Lead not found", 1);
        }
    }

}
