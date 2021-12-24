<?php


namespace OurProperty\Services;


use App\Models\AgentProfile;
use Exception;
use App\Models\Agency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Identification;
use App\Models\ConnectionService;
use App\Jobs\CreateHubspotProperty;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use OurProperty\Models\OurProperty;
use App\Models\ConnectionApplication;
use App\Services\AddressMapperService;
use App\Services\AuthService\JwtAuthService;
use App\Modules\OurProperty\Services\OurPropertyMapper;
use App\Models\ConnectionApplicationSecondaryACC as AuthorisedPerson;

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


    private $connectionApplicaton;

    private $userRequestData;

    /**
     * Generate token.
     *
     * @param array $leadInfo
     * @return array
     * @throws Exception
     */
    public function generateAccessToken(array $credentials): array|Exception
    {
        if ($this->email == $credentials['email'] && $this->password == $credentials['password']) {
            return $this->getAccessToken($credentials);
        } else {
            throw new Exception("id pass does not match");
        }
    }

    /**
     * Modify/Create access token.
     * docs https://github.com/firebase/php-jwt
     * tutorial https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
     *
     * @param array $credentials
     * @return array
     */
    private function getAccessToken(array $credentials): array
    {
        return JwtAuthService::getAccessToken($credentials['email']);
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

        $mapperService = new OurPropertyMapper();
        $addressService = new AddressMapperService();
        $agencyData = $this->getAgencyAndOffice();


        $this->connectionApplicaton->agency_id = $agencyData["agency"]?->id ?? 1;
        $this->connectionApplicaton->office_id = $agencyData["office"]?->id ?? 1;
        $this->connectionApplicaton->created_by = $agencyData["agent"]?->id ?? 1;
        $this->connectionApplicaton->source = ConnectionApplication::SOURCE_OUR_PROPERTY;

        //load Our Property
        $this->connectionApplicaton->title = $this->userRequestData->tenancy_title ?
            ucfirst($this->userRequestData->tenancy_title) : null;
        $this->connectionApplicaton->first_name = $this->userRequestData->tenancy_first_name ?? null;
        $this->connectionApplicaton->middle_name = $this->userRequestData->tenancy_middle_name ?? null;
        $this->connectionApplicaton->last_name = $this->userRequestData->tenancy_last_name ?? null;
        $this->connectionApplicaton->dob = $this->userRequestData->tenancy_dob ?? null;
        $this->connectionApplicaton->phone_type = $this->userRequestData->tenancy_phone_type ?
            $mapperService->mapPhoneType($this->userRequestData->tenancy_phone_type) : null;
        $this->connectionApplicaton->phone = $this->userRequestData->tenancy_phone_number ?? null;
        $this->connectionApplicaton->homephone = $this->userRequestData->tenancy_homephone ?? null;
        $this->connectionApplicaton->email = $this->userRequestData->tenancy_email ?? null;
        $this->connectionApplicaton->tenancy_type = $this->userRequestData->tenancy_type ?
            $mapperService->mapTenancy($this->userRequestData->tenancy_type) : null;
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
            $mapperService->mapYesNoToBool($this->userRequestData->is_email_billing) : null;
        $this->connectionApplicaton->property_type = $this->userRequestData->tenancy_property_type ?
            $mapperService->mapPropertyType($this->userRequestData->tenancy_property_type) : null;
        $this->connectionApplicaton->has_life_support = $this->userRequestData->tenancy_has_life_support ?
            $mapperService->mapYesNoToBool($this->userRequestData->tenancy_has_life_support) : null;
        $this->connectionApplicaton->has_solar = $this->userRequestData->tenancy_has_solar ?
            $mapperService->mapYesNoToBool($this->userRequestData->tenancy_has_solar) : null;


        $this->connectionApplicaton->nmi = $this->userRequestData->tenancy_nmi ?? null;
        $this->connectionApplicaton->mirn = $this->userRequestData->tenancy_mirn ?? null;
        $this->connectionApplicaton->unit_number = $this->userRequestData->tenancy_unit_number ?? null;
        $this->connectionApplicaton->street_number = $this->userRequestData->tenancy_street_number ?? null;
        $this->connectionApplicaton->street_name = $this->userRequestData->tenancy_street_name ?? null;
        $this->connectionApplicaton->billing_unit_number = $this->userRequestData->tenancy_billing_unit_number ?? null;
        $this->connectionApplicaton->billing_street_number = $this->userRequestData->tenancy_billing_street_number ?? null;
        $this->connectionApplicaton->billing_street_name = $this->userRequestData->tenancy_billing_street_name ?? null;
        $this->connectionApplicaton->billing_address_text = $this->userRequestData->tenancy_billing_address_text ?? null;
        $this->connectionApplicaton->billing_street_address = $this->userRequestData->tenancy_billing_street_address ?? null;;
        $this->connectionApplicaton->billing_city = $this->userRequestData->tenancy_billing_city ?? null;
        $this->connectionApplicaton->billing_state = $this->userRequestData->tenancy_billing_state ?
            $addressService->mapState($this->userRequestData->tenancy_billing_state) : null;
        $this->connectionApplicaton->billing_postcode = $this->userRequestData->tenancy_billing_postcode ?? null;
        $this->connectionApplicaton->is_renovation_on = $this->userRequestData->tenancy_is_renovation_on ?
            $mapperService->mapYesNoToBool($this->userRequestData->tenancy_is_renovation_on) : null;
    }

    /**
     * @return array
     * @throws Exception
     */
    #[ArrayShape(["agent" => "\App\Models\AgentProfile|null", "agency" => "\App\Models\Agency||null", "office" => "\App\Models\Office|null"])]
    private function getAgencyAndOffice(): array
    {
        $res = [
            "agent" => null,
            "agency" => null,
            "office" => null
        ];
        try {
            $res["agent"] = AgentProfile::whereHas(
                'user',
                fn(Builder $user) => $user->where('email', $this->userRequestData->agent_email)
            )->firstOrFail();

            $res["agency"] = Agency::query()
                ->where('id', $res["agent"]->agency_id)
                ->where('name', $this->userRequestData->agency_name)
                ->firstOrFail();

            $res["office"] = $res["agency"]->offices()->firstOrFail();


        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            #todo: send email to support


            throw new Exception("Provided `agency_name` or `agent_email` is not found in the system");
        }

        return $res;
    }

    public function setAttribute()
    {
        $ourProperty = new OurProperty();
        $ourProperty->all_fields_dump = json_encode($this->userRequestData->toArray());
        $ourProperty->connection_application_id = $this->connectionApplicaton->id;
        $ourProperty->lead_id = $this->userRequestData->our_property_lead_id;
        $ourProperty->agent_name = $this->userRequestData->agent_firstname;
        $ourProperty->agency_name = $this->connectionApplicaton->agency->name;
        $ourProperty->agent_email = $this->userRequestData->agent_email;
        $ourProperty->save();
        return $ourProperty;
    }

    public function createIdentification($leadId)
    {
        $mapperService = new OurPropertyMapper();
        $addressService = new AddressMapperService();

        $identification = new Identification();
        $identification->connection_application_id = $leadId;
        $identification->type = $this->userRequestData->tenancy_identification_type ?
            $mapperService->mapIdType($this->userRequestData->tenancy_identification_type) : null;
        $identification->card_number = $this->userRequestData->tenancy_identification_number ?? null;
        $identification->state = $this->userRequestData->tenancy_identification_state ?
            $addressService->mapState($this->userRequestData->tenancy_identification_state) : null;
        $identification->country = $this->userRequestData->tenancy_identification_country ?
            $addressService->mapCountry($this->userRequestData->tenancy_identification_country) : null;
        $identification->card_color = $this->userRequestData->tenancy_medicare_card_color ?
            strtoupper($this->userRequestData->tenancy_medicare_card_color) : null;
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

    public function createAuthorizedPerson($leadId)
    {
        $authorisedPerson = new AuthorisedPerson();
        $authorisedPerson->connection_application_id = $leadId;
        $authorisedPerson->title = $this->userRequestData->tenancy_secondary_title ?
            ucwords($this->userRequestData->tenancy_secondary_title) : null;
        $authorisedPerson->first_name = $this->userRequestData->tenancy_secondary_first_name ?? null;
        $authorisedPerson->last_name = $this->userRequestData->tenancy_secondary_last_name ?? null;
        $authorisedPerson->middle_name = $this->userRequestData->tenancy_secondary_middle_name ?? null;
        $authorisedPerson->email = $this->userRequestData->tenancy_secondary_email ?? null;
        $authorisedPerson->phone = $this->userRequestData->tenancy_secondary_phone_number ?? null;
        $authorisedPerson->role = $this->userRequestData->tenancy_secondary_permission_type ?
            AuthorisedPerson::ROLE_TYPE_MAPPER[$this->userRequestData->tenancy_secondary_permission_type] : null;
        $authorisedPerson->dob = $this->userRequestData->tenancy_secondary_dob ?? null;
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
        return trim($name);
    }
}
