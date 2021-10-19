<?php

namespace App\Services\Foxie;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Models\Foxie\SugerLead;
use Illuminate\Support\Facades\Log;
use App\Models\ConnectionApplication;

class SugerLeadService
{
    /**
     * @var mixed|null
     */
      private SugerLead $lead;
      private ConnectionApplication $connectionApplication;

    public function __construct(Request $request , $id = null)
    {
        $this->setApplicationLead($id);
        $this->setOfficeAndAgencyId();

        $this->connectionApplication->first_name = $request->first_name ?? 'Iron';
        $this->connectionApplication->last_name = $request->last_name ?? 'Man' ;
        $this->connectionApplication->email = $request->email1 ?? 'abc@hood.ai';
        $this->connectionApplication->dob = $request->birthdate  ?? '1991/08/09' ;
        $this->connectionApplication->moving_date = $request->move_in_date_c ?? '2021/10/02';
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
        $this->connectionApplication->created_at = $request->date_entered ?? '2021/10/02';
        $this->connectionApplication->updated_at = $request->date_modified ?? '2021/10/02';
        $this->connectionApplication->title = $request->salutation ?? 'Mr.';
        
        if( isset($this->lead)){
            $this->lead->created = $request->date_entered ?? '2021/10/02';
            $this->lead->updated = $request->date_modified ??  '2021/10/02';
        }
    }

    private function setOfficeAndAgencyId(){

        try {
            $agency = Agency::where('name' , "Foxie-Hood-Agent")->first();

            $this->connectionApplication->agency_id = $agency?->id ?? 1;
            $this->connectionApplication->office_id = $agency?->offices[0]?->id ?? 1;
        } catch (\Throwable $th) {
            Log::error("Please run FoxieSeeder");
        }


    }


    private function setApplicationLead($id = null){
        if($id == null){
            $this->connectionApplication = new ConnectionApplication;
            $this->lead = new SugerLead;
        }else{
            try {
                $this->connectionApplication = ConnectionApplication::findOrFail($id);
                $this->lead = $this->connectionApplication->SugerLead;
            } catch (\Throwable $th) {
                return [ "response" =>  ["status" => "failed", "message" =>  "Hood Lead Id: $id is not found"] , "status" => 404 ];
            }
        }
    }

    /**
     * @return array
     */
    public function create(): array
    {
        $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;
        $this->connectionApplication->save();
        $this->lead->all_fields_dump = json_encode(request()->all());
        $this->lead->connection_application_id = $this->connectionApplication->id;
        $this->lead->save();
        return [
                    "status" => "success" ,
                    "hood_lead_id" => $this->connectionApplication->id ,
                    "message" =>  "Hood lead has been added successfully" 
               ];
    }

    /**
     * @return array
     */
    public function update(Request $request, $id): array
    {
        try {
            $this->connectionApplication->save();
            $all_fields_dump =  json_decode($this->lead->all_fields_dump);
            $mergedUpdatedData =  collect($all_fields_dump)->merge($request->all());
            $this->lead->update(["all_fields_dump" => json_encode($mergedUpdatedData)]);
            return  [ 
                    "response" => [
                        "status"  => "success", 
                        "message" =>  "Your hood lead has been updated" 
                                  ], 
                        "status" => 200 
                                  ];

        } catch (\Throwable $th) {
            return [ "response" =>  ["status" => "failed", "message" =>  "Hood Lead Id: $id is not found"] , "status" => 404 ];
        }
    }

}
