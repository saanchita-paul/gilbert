<?php

namespace Foxie\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Agency;
use Foxie\Models\SugerLead;
use Illuminate\Http\Request;
use App\Models\Identification;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use App\Services\SearchAddress\GeocodeAddress;
use App\Services\SearchAddress\GeoCodeService;
use App\Modules\Foxie\Services\LeadStatusMapper;
use App\Services\SearchAddress\AddressModel;

class SugerLeadService
{
    /**
     * @var mixed|null
     */
    private SugerLead $lead;
    private ConnectionApplication $connectionApplication;
    private AddressModel $address;

    const TYPE_CREATE = 1;
    const TYPE_UPDATE = 2;

    const SERVICE_TYPE_POWER = 'power';
    const SERVICE_TYPE_GAS = 'gas';

    const TYPE_SERVICE = [
        'gas' => 'gas',
        'electricity' => 'power',
        'water' => 'water'
    ];

    const MAP_STATE_NSW = 'New South Wales';
    const MAP_STATE_VIC = 'Victoria';
    const MAP_STATE_QLD = 'Queensland';
    const MAP_STATE_SA  = 'South Australia';
    const MAP_STATE_NT  = 'Northern Territory';
    const MAP_STATE_TAS = 'Tasmania';
    const MAP_STATE_ACT = 'Australian Capital Territory';
    const MAP_STATE_WA = 'Western Australia';

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
     * @throws Exception
     */
    public function setGeoCodeToConnectionApplication(Request $request){
        if (empty($request->full_address_c)) {
            $this->address =  new AddressModel(
                unit_number: $request->primary_address_unit_c,
                street_number: $request->primary_address_number_c,
                street_name: trim($request->primary_address_street . " " . $request->primary_address_suffix_c),
                postcode: $request->primary_address_postalcode,
                city: $request->primary_address_city,
                state: $request->primary_address_state,
                country: "AUSTRALIA",
            );
        } else {
            $response = GeoCodeService::getAddressFromGeoCode($request->full_address_c);
            $geoCodeData = new GeocodeAddress($response);
            $this->address = $geoCodeData->getConnectionApplicationVersion();
        }
    }

    /**
     * Set attribute for create.
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private function setAttribute(Request $request){
        try {
            $dob = Carbon::parse($request->birthdate)->format("Y-m-d");
        } catch (\Exception $exception) {
            $dob = null;
            \Log::error('***problem in date dob parsing, sugerLeadService, setAttribute***' ,
            [ 'msg'  => $exception->getMessage(),
              'trace'=> $exception->getTraceAsString()
            ]);
        }

        $this->setGeoCodeToConnectionApplication($request);

        $this->connectionApplication->first_name = $request->first_name ?? null;
        $this->connectionApplication->last_name = $request->last_name ?? null ;
        $this->connectionApplication->source = ConnectionApplication::SOURCE_FOXIE ;
        $this->connectionApplication->email = $request->email1 ?? null;
        $this->connectionApplication->address_text = $this->address->address_text;
        $this->connectionApplication->dob = $dob ?? null;
        $this->connectionApplication->moving_date = date("Y-m-d", strtotime($request->move_in_date_c))  ?? null;
        $this->connectionApplication->street_address = $this->address->street_address ?? null;
        $this->connectionApplication->street_number = $this->address->street_number ?? null;
        $this->connectionApplication->street_name = $this->address->street_name ?? null;
        $this->connectionApplication->city = $this->address->city ?? null;
        $this->connectionApplication->postcode = $this->address->postcode ?? null;
        $this->connectionApplication->phone = $request->phone_mobile ?? null;
        $this->connectionApplication->tenancy_type = ConnectionApplication::TENANCY_MAPPING[$request->property_relationship_c] ?? null ;
        $this->connectionApplication->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[$request->customer_type_c] ?? null ;
        $this->connectionApplication->state = $this->address->state ?? null;
        $this->connectionApplication->country = $this->address->country ?? null;
        $this->connectionApplication->nmi = $request->electricity_nmi_c ?? null;
        $this->connectionApplication->mirn = $request->gas_mirn_c ?? null;
        $this->connectionApplication->unit_number = $this->address->unit_number ?? null;
        $this->connectionApplication->plan_type = $request->meter_plan_type_c ?? '3';
        // $this->connectionApplication->created_at = now();
        $this->connectionApplication->title = $request->salutation ?? null;
        // $this->connectionApplication->title = 'Mr';

        //SUGER LEADS TABLE
        $this->lead->service_address = $request->full_address_c ?? null;
        $this->lead->foxie_lead_source = $request->lead_source ?? null;
        $this->lead->compare_connect_id = $request->compareconnect_id_c ?? null;
        $this->lead->foxie_lead_source_description = $request->lead_source_description ?? null;
        $this->lead->office_branch = $request->office_c ?? null;
        $this->lead->agent_name = $request->agent_c ?? null;
        $this->lead->agency_id = $request->foxie_agents_id_c ?? null;
        $this->lead->agency_name = $request->office_c ?? null;
        $this->lead->lead_id = $request->id_c ?? null;
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
            try {
                $formattedDate = Carbon::parse($request->id_expiry_c)->format("Y-m-d");
            } catch (\Exception $exception) {
                $formattedDate = null;
                \Log::error('***problem in date id_expiry_c parsing , sugerLeadService, setIdentificationTable***' ,
                [ 'msg' => $exception->getMessage(),
                  'trace'=> $exception->getTraceAsString()
                ]);
            }
            $mappedType = Identification::TYPE_MAP[$request->id_type_c] ?? null;

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
        // SERVICE TYPE TABLE
        if(!isset($request->service_c)) {
            $services = ['water'];
        };
        info('checking service_c');
        // expected format example Electricity_Gas
        if($request->service_c) {
            $services = explode("_", strtolower($request->service_c));
            array_push($services, 'water'); // Water will be always added to the service
        }
        try {
            if($type == self::TYPE_UPDATE) $this->connectionApplication->connectionServices()->delete();
            foreach ($services as $value) {
                $this->connectionApplication->connectionServices()->create(
                    [
                        'service_type' => self::TYPE_SERVICE[$value] ,
                        'status' => ConnectionService::WATER_STATUS_IN_PROGRESS ,
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
     * @param Request $request
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
        $request->compareconnect_id_c ? $this->lead->compare_connect_id = $request->compareconnect_id_c : '';
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
     * @param Request $request
     * @return ConnectionApplication $newApplication
     */
    public function create(Request $request): ConnectionApplication
    {
//        $this->setGeoCodeToConnectionApplication($request);
//
//        dd($this->address);
        $this->connectionApplication = new ConnectionApplication;
        $this->lead = new SugerLead();
        $this->lead->all_fields_dump = json_encode(request()->all());
        $this->lead->save();


        $this->setOfficeAndAgencyId();
        $this->setAttribute($request);

        $this->connectionApplication->status = ConnectionApplication::STATUS_UNASSIGNED;
        $this->connectionApplication->save();
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
     * @param Request $request
     * @param int $id
     * @return bool $successOrFailed
     * @throws Exception
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
     * fetching lead by ID
     *
     * @param $id
     *
     * @return array|mixed
     *
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
     * Fetching leads using date range
     *
     * @param $from
     * @param $to
     *
     * @return array
     *
     * @throws Exception
     */
    public function get($from, $to): array
    {
        $identificationColumns = 'identification:id,connection_application_id,expire_date,type';
        $leads =  ConnectionApplication::with([$identificationColumns])
            ->where('updated_at', '>=', $from)
            ->where('updated_at', '<=', $to)
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

    public static function getAddressFromGeoCode(string $address){
        try {
            $response = Http::withHeaders([
                "content-type"    => "application/json",
                "Accept"          => "*/*",
            ])
            ->get(config('geocode.baseUrl'),
                        [ "key"     => config('geocode.apiKey'),
                          "address" => $address ]);

            $geoCodeData = new GeocodeAddress($response);
            return $geoCodeData;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

}
