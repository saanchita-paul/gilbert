<?php


namespace OurProperty\Services;


use App\Models\Agency;
use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Modules\OurProperty\Services\OurPropertyMapper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OurProperty\Models\OurProperty;

class CreateOurPropertyService
{
    /**
     * credetial email
     *
     * @var string
    */
    private string $email = "lenin@hood.ai";
    /**
     * credetial password
     *
     * @var string
    */
    private string $password = "Hu435567";

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
    private string $expires_at = "2022-10-27 04:38:00";

    private $connectionApplicaton;

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
        $this->prepareConnectionApp($requestData);
        $this->connectionApplicaton->status = ConnectionApplication::STATUS_UNASSIGNED;
        $this->connectionApplicaton->save();

        $this->createIdentification($requestData, $this->connectionApplicaton->id);

        // save the incoming date to our service table
        return $this->setAttribute($requestData);

    }

    private function prepareConnectionApp($requestData)
    {
        try {

            $mapperService = new OurPropertyMapper();

            $agency = Agency::where('name' , "Our-Property-Agency")->firstOrFail();
            if(!$agency) throw new Exception('Please run OurPropertySeeder');

            $this->connectionApplicaton->agency_id = $agency?->id ?? 1;
            $this->connectionApplicaton->office_id = $agency?->offices[0]?->id ?? 1;
            $this->connectionApplicaton->source = ConnectionApplication::SOURCE_OUR_PROPERTY;

            //load Our Property
            $this->connectionApplicaton->title = $requestData->tenancy_title ?? null;
            $this->connectionApplicaton->first_name = $requestData->tenancy_first_name ?? null;
            $this->connectionApplicaton->middle_name =  $requestData->tenancy_middle_name ?? null;
            $this->connectionApplicaton->last_name = $requestData->tenancy_last_name ?? null;
            $this->connectionApplicaton->dob = $requestData->tenancy_dob ?? null;
            $this->connectionApplicaton->phone_type = $requestData->tenancy_phone_type ?
                $mapperService->mapPhoneType($requestData->tenancy_phone_type): null;
            $this->connectionApplicaton->phone = $requestData->tenancy_phone_number ?? null;
            $this->connectionApplicaton->homephone = $requestData->tenancy_homephone ?? null;
            $this->connectionApplicaton->email = $requestData->tenancy_email ?? null;
            $this->connectionApplicaton->tenancy_type = $requestData->tenancy_type ?
                $mapperService->mapTenancy($requestData->tenancy_type): null;
            $this->connectionApplicaton->moving_date = $requestData->tenancy_moving_date ?? null;
            $this->connectionApplicaton->additional_instruction = $requestData->additional_instruction ?? null;
            $this->connectionApplicaton->street_address = $requestData->street_address ?? null;
            $this->connectionApplicaton->city = $requestData->tenancy_city ?? null;
            $this->connectionApplicaton->postcode = $requestData->tenancy_postcode ?? null;
            $this->connectionApplicaton->state = $requestData->tenancy_state ?? null;
            $this->connectionApplicaton->country = $requestData->tenancy_country ?? null;
            $this->connectionApplicaton->address_text = $requestData->tenancy_address_text ?? null;
            $this->connectionApplicaton->is_email_billing = $requestData->is_email_billing ?
                $mapperService->mapYesNoToBool( $requestData->is_email_billing): null;
            $this->connectionApplicaton->property_type = $requestData->tenancy_property_type?
                $mapperService->mapPropertyType( $requestData->tenancy_property_type): null;
            $this->connectionApplicaton->has_life_support = $requestData->tenancy_has_life_support ?
                $mapperService->mapYesNoToBool( $requestData->tenancy_has_life_support): null;
            $this->connectionApplicaton->has_solar = $requestData->has_solar ?
                $mapperService->mapYesNoToBool( $requestData->has_solar): null;

            $this->connectionApplicaton->nmi = $requestData->tenancy_nmi ?? null;
            $this->connectionApplicaton->mirn = $requestData->tenancy_mirn ?? null;
            $this->connectionApplicaton->unit_number = $requestData->tenancy_unit_number ?? null;
            $this->connectionApplicaton->street_number = $requestData->tenancy_street_number ?? null;
            $this->connectionApplicaton->street_name = $requestData->tenancy_street_name ?? null;
            $this->connectionApplicaton->billing_unit_number = $requestData->tenancy_billing_unit_number ?? null;
            $this->connectionApplicaton->billing_street_number = $requestData->tenancy_billing_street_number ?? null;
            $this->connectionApplicaton->billing_street_name =$requestData->tenancy_billing_street_name ?? null;
            $this->connectionApplicaton->billing_address_text =$requestData->tenancy_billing_address_text ?? null;
            $this->connectionApplicaton->billing_street_address = $requestData->tenancy_billing_street_address ?? null;;
            $this->connectionApplicaton->billing_city = $requestData->tenancy_billing_city ?? null;
            $this->connectionApplicaton->billing_postcode = $requestData->tenancy_billing_postcode ?? null;
            $this->connectionApplicaton->is_renovation_on = $requestData->tenancy_is_renovation_on ?
                $mapperService->mapYesNoToBool( $requestData->tenancy_is_renovation_on): null;




        } catch (\Throwable $th) {
            Log::error("Please run OurPropertySeeder , php artisan db:seed --class=Seeder");
        }
    }

    public function setAttribute($requestData)
    {
        $ourProperty = new OurProperty();
        $ourProperty->all_fields_dump = json_encode($requestData->toArray());
        $ourProperty->connection_application_id = $this->connectionApplicaton->id;
        $ourProperty->lead_id = $requestData->lead_id;
        $ourProperty->agent_name = $requestData->agent_firstname;
        $ourProperty->agency_name = $this->connectionApplicaton->agency->name;
        $ourProperty->agent_email =$requestData->agent_email;
        $ourProperty->save();
        return $ourProperty;
    }

    public function createIdentification($requestData, $leadId)
    {
        $mapperService = new OurPropertyMapper();

        $identification = new Identification();
        $identification->connection_application_id = $leadId;
        $identification->type = $requestData->tenancy_identification_type ?
            $mapperService->mapIdType($requestData->tenancy_identification_type): null;
        $identification->card_number = $requestData->tenancy_identification_number ?? null;
        $identification->state = $requestData->tenancy_identification_state ?? null;
        $identification->country = $requestData->tenancy_identification_country ?? null;
        $identification->card_color = $requestData->tenancy_medicare_card_color ?? null;
        $identification->special_number = $requestData->tenancy_medicare_reference_number ?? null;
        $identification->expire_date = $requestData->tenancy_indentification_expire_date ?? null;
        $identification->save();
    }
}
