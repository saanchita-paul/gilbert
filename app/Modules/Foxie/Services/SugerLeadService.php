<?php

namespace Foxie\Services;

use Exception;
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
        $this->connectionApplication->dob = date("Y-m-d", strtotime($request->birthdate)) ?? '1991/08/09';
        $this->connectionApplication->moving_date = date("Y-m-d", strtotime($request->move_in_date_c))  ?? '2021/10/02';
        $this->connectionApplication->address_unit = $request->primary_address_unit_c ?? '';
        $this->connectionApplication->street_address = $request->primary_address_street ?? 'Queen Street';
        $this->connectionApplication->city = $request->primary_address_city ?? 'Brisbane City';
        $this->connectionApplication->postcode = $request->alt_address_postcode ?? '4000';
        $this->connectionApplication->phone = $request->phone_mobile ?? '';
        $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_MAPPING[$request->property_relationship_c] ?? null ;
        $this->connectionApplication->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[$request->customer_type_c] ?? null ;
        $this->connectionApplication->state = $request->primary_address_state ?? 'Queensland';
        $this->connectionApplication->country = $request->primary_address_country ?? 'Australia';
        $this->connectionApplication->nmi = $request->electricity_nmi_c ?? '';
        $this->connectionApplication->mirn = $request->gas_mirn_c ?? '';
        $this->connectionApplication->unit_number = $request->primary_address_unit_c ?? '';
        $this->connectionApplication->plan_type = $request->meter_plan_type_c ?? '3';
        $this->connectionApplication->created_at = now();
        // $this->connectionApplication->title = $request->salutation ?? 'Mr';
        $this->connectionApplication->title = 'Mr';

        //SUGER LEADS TABLE
        $this->lead->service_address = $request->full_address_c ?? '';
        $this->lead->office_branch = $request->office_c ?? '';
        $this->lead->agent_name = $request->agent_c ?? '';
        $this->lead->agency_id = $request->foxie_agents_id_c ?? '';
        $this->lead->agency_name = $request->office_C ?? '';
        $this->lead->lead_id = $request->id_c ?? '';
        $this->lead->created = $request->date_entered ?? null;
        $this->lead->updated = $request->date_modified ?? null;

        //set identification table
        $this->setIdentificationTable($request , new Identification , self::TYPE_CREATE);
        
    }
    
    private function setIdentificationTable(Request $request , $identification , $type){
        //IDENTIFICATION TABLE
        try {
            // if($request->id_expiry_c){
            //     $identification->expire_date = $request->id_expiry_c;
            //     $identification->connection_application_id = $this->connectionApplication->id;
            //     $identification->save();
            // }

            if($type == self::TYPE_CREATE){
                $identification->expire_date = $request->id_expiry_c ?? null;
                $identification->type = Identification::TYPE_MAP[$request->id_type_c] ?? null ;
            }else{
                $request->customer_type_c ? $identification->type = Identification::TYPE_MAP[$request->id_type_c]: '';
                $request->id_expiry_c ? $identification->type = $request->id_type_c : '';
            }
            $identification->connection_application_id = $this->connectionApplication->id;
            $identification->save();

        } catch (\Exception $ex) {
                \Log::error('problem in identification table');
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
        $request->primary_address_street ? $this->connectionApplication->street_address = $request->primary_address_street : '';
        $request->primary_address_city ? $this->connectionApplication->city = $request->primary_address_city : '';
        $request->alt_address_postcode ? $this->connectionApplication->postcode = $request->alt_address_postcode : '';
        $request->primary_address_state ? $this->connectionApplication->state = $request->primary_address_state : '';
        $request->primary_address_country ? $this->connectionApplication->country = $request->primary_address_country : '';
        $request->phone_mobile ? $this->connectionApplication->phone = $request->phone_mobile : '';
        $request->property_relationship_c ? $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_MAPPING[$request->property_relationship_c] ?? $this->connectionApplication->tenancy_type : '';
        
        $request->customer_type_c ? $this->connectionApplication->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[$request->customer_type_c] ?? $this->connectionApplication->property_type : '';
        
        $request->electricity_nmi_c ? $this->connectionApplication->nmi = $request->electricity_nmi_c : '';
        $request->gas_mirn_c ? $this->connectionApplication->mirn = $request->gas_mirn_c : '';
        $request->primary_address_unit_c ? $this->connectionApplication->unit_number = $request->primary_address_unit_c : '';
        $request->meter_plan_type_c ? $this->connectionApplication->plan_type = $request->meter_plan_type_c : '';
        $this->connectionApplication->updated_at = now();
        
        
        //SUGER LEADS TABLE
        $request->full_address_c ? $this->lead->service_address = $request->full_address_c : '';
        $request->office_c ? $this->lead->office_branch = $request->office_c : '';
        $request->foxie_agents_id_c ? $this->lead->agency_id = $request->foxie_agents_id_c : '';
        $request->agent_c ? $this->lead->agent_name = $request->agent_c : '';
        $request->id_c ? $this->lead->lead_id = $request->id_c : '';
        $request->office_C ? $this->lead->agency_name = $request->office_C : '';
        $this->lead->updated = $request->date_modified ?? null;


        //set connetion application
        $this->connectionApplication->identification ? 
        $this->setIdentificationTable($request , $this->connectionApplication->identification , self::TYPE_UPDATE ) : 
        $this->setIdentificationTable($request , new Identification , self::TYPE_CREATE ) ; 
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

        $this->setIdentificationTable($request , new Identification , self::TYPE_CREATE ) ; 

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
            
            return true;
        } catch (\Exception $ex) {
            Log::error("Problem in updating");
            Log::error($ex->getMessage());
            throw new Exception("Error Processing Request", 1);
        }
    }

    /**
     * Show the specified resource in storage.
     *
     * @param  String $fromDate
     * @param  String $toDate
     * @param  int  $id
     * @return Ojbect $leads
     */
    public function show(String $from = null , String $to = null , $id = null) : Object
    {   
        try {
            $leads = null;
            if($id == null){
                $leads =  ConnectionApplication::with(['identification'])->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('source' , ConnectionApplication::SOURCE_FOXIE )->get();
            }else{
                $leads  = ConnectionApplication::with(['identification'])->where( 'source' , ConnectionApplication::SOURCE_FOXIE )->where('id' , $id)->get()[0];
                $leads ?? throw new Exception("Error Processing Request", 1);
            }
            return $leads;
        } catch (\Exception $ex) {
                Log::error("Problem in retrieving data");
                Log::error($ex->getMessage());
                throw new Exception("Lead not found", 1);
        }
    }

}
