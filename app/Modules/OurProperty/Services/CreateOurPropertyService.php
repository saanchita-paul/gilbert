<?php


namespace OurProperty\Services;


use App\Jobs\CreateHubspotProperty;
use App\Models\Agency;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC as AuthorisedPerson;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Modules\OurProperty\Services\OurPropertyMapper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OurProperty\Models\OurProperty;
use App\Services\AddressMapperService;

class CreateOurPropertyService
{
    /**
     * credetial email
     *
     * @var string
    */
    private string $email = "hoodintegration@ourproperty.com.au";
    /**
     * credetial password
     *
     * @var string
    */
    private string $password = "h00dtwork266_tsh";

    /**
     * server generated access token
     *
     * @var string
    */
    private string $access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE5ZjFmZmJlMjY1NWM3ZjFkODljMDE3ZWI0NWI5ZGE2ODZjZDE3ZmY1NmY0OTA5NGUwZmVhYmYwNTQ1ZDQ4YjU1MmFjNjM1ZGY1ZjYwM2QxIn0.eyJhdWQiOiIxIiwianRpIjoiYTlmMWZmYmUyNjU1YzdmMWQ4OWMwMTdlYjQ1YjlkYTY4NmNkMTdmZjU2ZjQ5MDk0ZTBmZWFiZjA1NDVkNDhiNTUyYWM2MzVkZjVmNjAzZDEiLCJpYXQiOjE2MzUzMDk0ODAsIm5iZiI6MTYzNTMwOTQ4MCwiZXhwIjoxNjY2ODQ1NDgwLCJzdWIiOiIyNyIsInNjb3BlcyI6W119.LOo49acFNxSC9gQXnJEmZRWk74Hf_8nEZemZI-5zDOH7BgM2CFXcQ12ii5NbZmHNjt4lPTh2vYDO-m-YEYvd4SMTLQOc1mSBi8AkjbCNpnNiSPKnlBLjRzRE8mXWAxpevvXmcCL_KDrslTk348jHbW1L1SeE8J0X6uosd4L3pef0skfMdG0QMD9XoGSIfD01bjKIxcRPbOpmpE7Tqnuz9aKpF4UiN5Gd0VSBa3_TRkMgJm7OHZCcHg33FGJHYrcFAcSAPq5rJ_3pNCoM0emNXKsDxua4Yb-esKBfdHKTBzknnu8yEnZaJ61iJFHCm5IqhRmAXm8jG0t0hD0oKhLrPNs0HpmQlSNQeUly8OgQxhj2PGejxAvpgmTNJ4eC_UcXQhIJdHpyhwVB2CdvNApSGWQQxagGeqeE54ZtudxU0qILClgXZeCSo40KeASa_1T7-TJduJbZflUEteSk9m_KmmNccrV-99gcY4n8LDGVwu6ta8ZrfxeeQv6H_hP1E6ghFAJjvnJSJVWqCshxHkVltSWlqj1ArLEq-UXcCFqV96FnRUAy_o_B9fy_LdlraQ_IrMCLlojBjdI5vdSf7TZLjp11aTR8nKGEVuP6rPUyMpOWds7n6F9M9dw86NFYvT1gWhZwVrbNZ1euAOz4hyS4g98BvE1NHrJ-0_GjeGTNISc";
    /**
     * server generated token_type
     *
     * @var string
    */
    private string $token_type = "Bearer";
    /**
     * server generated expiry date_type
     *
     * @var string
    */
    private string $expires_at = "2022-12-27 04:38:00";

    private $connectionApplicaton;

    private $userRequestData;

    /**
     * Generate token.
     *
     * @param  array  $leadInfo
     * @return array
     * @throws Exception
     */
    public function generateAccessToken(array $credentials) : array|Exception{
        if( $this->email == $credentials['email'] && $this->password == $credentials['password']  ){
            return [
                "access_token" => $this->getAccessToken(),
                "token_type" => $this->token_type,
                "expires_at" => $this->expires_at,
            ];
        }else{
            throw new Exception("id pass does not match");
        }
    }

    /**
     * Modify/Create access token.
     *
     * @param  void
     * @return string
     */
    private function getAccessToken() : string {
        return $this->access_token;
    }



    public function create(Request $requestData)
    {
        $this->connectionApplicaton = new ConnectionApplication();

        // preparing connection app for Our-Property
        $this->userRequestData = $requestData;

        // saving all data to our property table
        $ourProperty = $this->storeToOurProperty();

        $this->prepareConnectionApp();
        $this->connectionApplicaton->status = ConnectionApplication::STATUS_UNASSIGNED;
        $this->connectionApplicaton->save();


        // set the lead id in our property data
        $ourProperty->connection_application_id = $this->connectionApplicaton->id;
        $ourProperty->save();


        try {
            $this->createIdentification($this->connectionApplicaton->id);
            $this->createService($requestData->tenancy_service_type, $this->connectionApplicaton->id);
            $this->createAuthorizedPerson($this->connectionApplicaton->id);
            CreateHubspotProperty::dispatch($this->connectionApplicaton->id);


        } catch (Exception $ex) {
            \Log::error("Lead create successful, Identification or Service or Authorization creation fail");
            \Log::error($ex->getMessage());
            \Log::error($ex->getTraceAsString());
        }




        return $ourProperty;

    }

    private function prepareConnectionApp()
    {
        try {

            $mapperService = new OurPropertyMapper();
            $addressService = new AddressMapperService();

            $agency = Agency::where('name' , "Our-Property-Agency")->firstOrFail();
            if(!$agency) throw new Exception('Please run OurPropertySeeder');

            $this->connectionApplicaton->agency_id = $agency?->id ?? 1;
            $this->connectionApplicaton->office_id = $agency?->offices[0]?->id ?? 1;
            $this->connectionApplicaton->source = ConnectionApplication::SOURCE_OUR_PROPERTY;

            //load Our Property
            $this->connectionApplicaton->title = $this->userRequestData->tenancy_title ?
                ucfirst($this->userRequestData->tenancy_title): null;
            $this->connectionApplicaton->first_name = $this->userRequestData->tenancy_first_name ?? null;
            $this->connectionApplicaton->middle_name =  $this->userRequestData->tenancy_middle_name ?? null;
            $this->connectionApplicaton->last_name = $this->userRequestData->tenancy_last_name ?? null;
            $this->connectionApplicaton->dob = $this->userRequestData->tenancy_dob ?? null;
            $this->connectionApplicaton->phone_type = $this->userRequestData->tenancy_phone_type ?
                $mapperService->mapPhoneType($this->userRequestData->tenancy_phone_type): null;
            $this->connectionApplicaton->phone = $this->userRequestData->tenancy_phone_number ?? null;
            $this->connectionApplicaton->homephone = $this->userRequestData->tenancy_homephone ?? null;
            $this->connectionApplicaton->email = $this->userRequestData->tenancy_email ?? null;
            $this->connectionApplicaton->tenancy_type = $this->userRequestData->tenancy_type ?
                $mapperService->mapTenancy($this->userRequestData->tenancy_type): null;
            $this->connectionApplicaton->moving_date = $this->userRequestData->tenancy_moving_date ?? null;
            $this->connectionApplicaton->additional_instruction = $this->userRequestData->additional_instruction ?? null;
            $this->connectionApplicaton->street_address = $this->userRequestData->tenancy_street_address ?? null;
            $this->connectionApplicaton->city = $this->userRequestData->tenancy_city ?? null;
            $this->connectionApplicaton->postcode = $this->userRequestData->tenancy_postcode ?? null;
            $this->connectionApplicaton->state = $this->userRequestData->tenancy_state ?
                $addressService->mapState($this->userRequestData->tenancy_state) : null;
            $this->connectionApplicaton->country = $this->userRequestData->tenancy_country ?
                $addressService->mapCountry($this->userRequestData->tenancy_country) : null;
            $this->connectionApplicaton->address_text = $this->userRequestData->tenancy_address_text ?? null;
            $this->connectionApplicaton->is_email_billing = $this->userRequestData->is_email_billing ?
                $mapperService->mapYesNoToBool( $this->userRequestData->is_email_billing): null;
            $this->connectionApplicaton->property_type = $this->userRequestData->tenancy_property_type?
                $mapperService->mapPropertyType( $this->userRequestData->tenancy_property_type): null;
            $this->connectionApplicaton->has_life_support = $this->userRequestData->tenancy_has_life_support ?
                $mapperService->mapYesNoToBool( $this->userRequestData->tenancy_has_life_support): null;
            $this->connectionApplicaton->has_solar = $this->userRequestData->tenancy_has_solar ?
                $mapperService->mapYesNoToBool( $this->userRequestData->tenancy_has_solar): null;




            $this->connectionApplicaton->nmi = $this->userRequestData->tenancy_nmi ?? null;
            $this->connectionApplicaton->mirn = $this->userRequestData->tenancy_mirn ?? null;
            $this->connectionApplicaton->unit_number = $this->userRequestData->tenancy_unit_number ?? null;
            $this->connectionApplicaton->street_number = $this->userRequestData->tenancy_street_number ?? null;
            $this->connectionApplicaton->street_name = $this->userRequestData->tenancy_street_name ?? null;
            $this->connectionApplicaton->billing_unit_number = $this->userRequestData->tenancy_billing_unit_number ?? null;
            $this->connectionApplicaton->billing_street_number = $this->userRequestData->tenancy_billing_street_number ?? null;
            $this->connectionApplicaton->billing_street_name =$this->userRequestData->tenancy_billing_street_name ?? null;
            $this->connectionApplicaton->billing_address_text =$this->userRequestData->tenancy_billing_address_text ?? null;
            $this->connectionApplicaton->billing_street_address = $this->userRequestData->tenancy_billing_street_address ?? null;;
            $this->connectionApplicaton->billing_city = $this->userRequestData->tenancy_billing_city ?? null;
            $this->connectionApplicaton->billing_state = $this->userRequestData->tenancy_billing_state ?
                $addressService->mapState($this->userRequestData->tenancy_billing_state) : null;
            $this->connectionApplicaton->billing_postcode = $this->userRequestData->tenancy_billing_postcode ?? null;
            $this->connectionApplicaton->is_renovation_on = $this->userRequestData->tenancy_is_renovation_on ?
                $mapperService->mapYesNoToBool( $this->userRequestData->tenancy_is_renovation_on): null;




        } catch (\Throwable $th) {
            Log::error("Please run OurPropertySeeder , php artisan db:seed --class=Seeder");
        }
    }

    public function setAttribute()
    {
        $ourProperty = new OurProperty();
        $ourProperty->all_fields_dump = json_encode($this->userRequestData->toArray());
        $ourProperty->connection_application_id = $this->connectionApplicaton->id;
        $ourProperty->lead_id = $this->userRequestData->our_property_lead_id;
        $ourProperty->agent_name = $this->userRequestData->agent_firstname;
        $ourProperty->agency_name = $this->connectionApplicaton->agency->name;
        $ourProperty->agent_email =$this->userRequestData->agent_email;
        $ourProperty->save();
        return $ourProperty;
    }

    public function createIdentification( $leadId)
    {
        $mapperService = new OurPropertyMapper();
        $addressService = new AddressMapperService();

        $identification = new Identification();
        $identification->connection_application_id = $leadId;
        $identification->type = $this->userRequestData->tenancy_identification_type ?
            $mapperService->mapIdType($this->userRequestData->tenancy_identification_type): null;
        $identification->card_number = $this->userRequestData->tenancy_identification_number ?? null;
        $identification->state = $this->userRequestData->tenancy_identification_state ?
            $addressService->mapState($this->userRequestData->tenancy_identification_state) : null;
        $identification->country = $this->userRequestData->tenancy_identification_country ?
            $addressService->mapCountry($this->userRequestData->tenancy_identification_country) : null;
        $identification->card_color = $this->userRequestData->tenancy_medicare_card_color ?
            strtoupper( $this->userRequestData->tenancy_medicare_card_color):null;
        $identification->special_number = $this->userRequestData->tenancy_medicare_reference_number ?? null;
        $identification->expire_date = $this->userRequestData->tenancy_indentification_expire_date ?? null;
        $identification->save();
    }

    public function createService($ourPropertyServices, $leadId)
    {
        foreach ($ourPropertyServices as $service) {
            $connectionService = new ConnectionService();
            $connectionService->service_type = strtolower($service);
            $connectionService->status = ConnectionService::STATUS_EA_PROCESSINF;
            $connectionService->connection_application_id = $leadId;
            $connectionService->save();
        }
    }

    public function createAuthorizedPerson( $leadId)
    {
        $authorisedPerson = new AuthorisedPerson();
        $authorisedPerson->connection_application_id = $leadId;
        $authorisedPerson->title = $this->userRequestData->tenancy_secondary_title ?
            ucwords( $this->userRequestData->tenancy_secondary_title): null;
        $authorisedPerson->first_name = $this->userRequestData->tenancy_secondary_first_name ?? null;
        $authorisedPerson->last_name = $this->userRequestData->tenancy_secondary_last_name ?? null;
        $authorisedPerson->middle_name = $this->userRequestData->tenancy_secondary_middle_name ?? null;
        $authorisedPerson->email = $this->userRequestData->tenancy_secondary_email ?? null;
        $authorisedPerson->phone = $this->userRequestData->tenancy_secondary_phone_number ?? null;
        $authorisedPerson->role = $this->userRequestData->tenancy_secondary_permission_type ?
            AuthorisedPerson::ROLE_TYPE_MAPPER[$this->userRequestData->tenancy_secondary_permission_type] : null;
        $authorisedPerson->dob =  $this->userRequestData->tenancy_secondary_dob ?? null;
        $authorisedPerson->save();

    }

    public function storeToOurProperty()
    {
        $ourProperty = new OurProperty();
        $ourProperty->all_fields_dump = json_encode($this->userRequestData->toArray());
        $ourProperty->lead_id = $this->userRequestData->our_property_lead_id ?? null;
        $ourProperty->agent_name = $this->getAgentName() ?? null;
        $ourProperty->agency_name = $this->userRequestData->agency_name ?? null;
        $ourProperty->agent_email = $this->userRequestData->agent_email ?? null;
        $ourProperty->save();
        return $ourProperty;
    }

    /**
     * @return string
     */
    private function getAgentName(): string
    {
        $name = $this->userRequestData->agent_firstname ?? '';
        $name = $this->userRequestData->agent_lastname ? "$name " . $this->userRequestData->agent_lastname : $name;
        return  trim($name);
    }
}
