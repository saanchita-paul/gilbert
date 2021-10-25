<?php

namespace App\Services\Foxie;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Models\Foxie\SugerLead;
use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;
use Exception;

class SugerLeadService
{
    /**
     * @var mixed|null
     */
    private SugerLead $lead;
    private ConnectionApplication $connectionApplication;

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
        $this->connectionApplication->state = $request->primary_address_state ?? 'Queensland';
        $this->connectionApplication->country = $request->primary_address_country ?? 'Australia';
        $this->connectionApplication->nmi = $request->electricity_nmi_c ?? '';
        $this->connectionApplication->mirn = $request->gas_mirn_c ?? '';
        $this->connectionApplication->unit_number = $request->primary_address_unit_c ?? '';
        $this->connectionApplication->plan_type = $request->meter_plan_type_c ?? '3';
        $this->connectionApplication->created_at = now();
        $this->connectionApplication->updated_at = now();
        // $this->connectionApplication->title = $request->salutation ?? 'Mr';
        $this->connectionApplication->title = 'Mr';

        $this->lead->created = now();
        $this->lead->updated = now();
    }


    private function setOfficeAndAgencyId(){
        try {
            $agency = Agency::where('name' , "Foxie-Hood-Agent")->first();
            $this->connectionApplication->agency_id = $agency?->id ?? 1;
            $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
            if(!$agency) throw new Exception('Please run FoxieSeeder');
        } catch (\Throwable $th) {
            Log::error("Please run FoxieSeeder");
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
            $this->connectionApplication = ConnectionApplication::findOrFail($id);
            $this->lead = $this->connectionApplication->SugerLead;
            
            $this->setAttribute($request);
            
            $this->connectionApplication->save();
            $all_fields_dump =  json_decode($this->lead->all_fields_dump);
            $mergedUpdatedData =  collect($all_fields_dump)->merge($request->all());
            $this->lead->update(["all_fields_dump" => json_encode($mergedUpdatedData)]);
            
            return true;
        } catch (\Throwable $th) {
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
    public function show(String $from = null , String $to = null , int $id = null) : Object
    {   
        try {
            $leads = '';
            if($id == null){
                $leads =  ConnectionApplication::where('moving_date', '>=', $from)->where('moving_date', '<=', $to)->get();
            }else{
                $leads  = ConnectionApplication::findOrFail($id);
            }
            return $leads;
        } catch (\Throwable $th) {
            throw new Exception("Lead not found", 1);
        }
    }

}
