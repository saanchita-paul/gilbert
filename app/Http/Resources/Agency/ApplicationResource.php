<?php

namespace App\Http\Resources\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\User;
use App\Services\TimeZoneService;
use App\Services\Utility\StateMapService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use App\Services\Agency\AgentStatusProgressMapper;
use App\Services\Agency\AgentServiceApplicationStatusMapper;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

class ApplicationResource extends JsonResource
{
    /**
     * @var mixed|null
     */
    private mixed $tsa;

    public function __construct($resource, $tsa = [])
    {
        parent::__construct($resource);
        $this->tsa = is_array($tsa) ? $tsa : [];
    }
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'international_phone' => $this->international_phone,
            'homephone' => $this->homephone,
            'phone_type' => $this->phone_type,
            'tenancy_type' => $this->tenancy_type,
            'date_of_birth' => $this->dob,
            'moving_date' => $this->moving_date,
            'address_unit' => $this->address_unit,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'state' => $this->getStateFull($this->state),
            'state_short' => $this->state_short,
            'country' => $this->country,
            'additional_instruction' => $this->additional_instruction,
            'address_text' => $this->address_text,
            'services' => $this->getConnectionServices($this->connectionServices),
            'connection_services' => $this->mapService($this->connectionServices),
            'tsa_call_histories' => $this->mapTsaService($this->tsa),
            'identification' => $this->identification,
            'family_violance' => isset($this->family_violance) ? $this->family_violance : 3,
            'is_renovation_on' => isset($this->is_renovation_on) ? $this->is_renovation_on : 0,
            'has_electricity' => isset($this->has_electricity) ? $this->has_electricity : 1,
            'inspection_time' => $this->inspection_time,
            'is_email_billing' => $this->is_email_billing,
            'nmi' => $this->nmi,
            'mirn' => $this->mirn,
            'property_type' => $this->property_type,
            'has_life_support' => $this->has_life_support,
            'has_solar' => $this->has_solar,
            'office_id' => $this->office_id,
            'agency_id' => $this->agency_id,
            'created_by' => $this->created_by,
            'created_by_agent' => $this->createdBy,
            'assigned_to' => $this->assigned_to,
            'agent_profile' => $this->assignedTo,
            'status' => $this->status,
            'street_number' => $this->street_number,
            'street_name' => $this->street_name,
            'street_name_only' => $this->street_name_only,
            'unit_number' => $this->unit_number,
            'billing_unit_number' => $this->billing_unit_number,
            'billing_street_number' => $this->billing_street_number,
            'billing_street_name' => $this->billing_street_name,
            'billing_address_text' => $this->billing_address_text,
            'billing_address_unit' => $this->billing_unit_number,
            'billing_street_address' => $this->billing_street_address,
            'billing_city' => $this->billing_city,
            'billing_state' =>$this->getStateFull($this->billing_state),
            'billing_street_type' => $this->billing_street_type,
            'billing_postcode' => $this->billing_postcode,
            'is_billing_same' => $this->is_billing_same,
            'is_contacted' => $this->is_contacted,
            'tsa_call_status' => $this->tsa_call_status,
            'is_auto_water_submit' => $this->is_auto_water_submit,
            'fast_connect_customer_reference' => $this->fast_connect_customer_reference,
            'authorizedPersonName' => $this->getAuthoizedPersonName(),
            'agent_name' => $this->getAgentName(),
            'agency_office' => $this->getAgencyName(),
            'lead_source' => $this->SugerLead?->foxie_lead_source,
            'lead_source_description' => $this->SugerLead?->foxie_lead_source_description,
            'source' => $this->source,
            'plan_type' => $this->mapPlan($this->plan_type),
            'is_temporary_connection' => $this->is_temporary_connection,
            'connection_end_date' => $this->connection_end_date,
            'unit_number' => $this->unit_number,
            'street_type' => $this->street_type,
            'mannual_address' => $this->mannual_address,

            'billing_mannual_address' => $this->billing_mannual_address,
            'billing_state_short' => $this->billing_state_short,
            'billing_street_name_only' => $this->billing_street_name_only,
            'is_address_complete' => $this->is_address_complete,
            'billing_is_address_complete' => $this->billing_is_address_complete,

            #todo: set timezone dynamically based on daylight saving
            'created_at' => (new Carbon($this->created_at, TimeZoneService::getTimeZoneInt()))->format('d/m/Y h:m a'),
            'submitted_by' => $this->submittedBy(),
            'submitted_at' => $this->submittedAt(),
            'after_hour_payee' => $this->after_hour_payee,

            'is_email_marketing' => $this->is_email_marketing,
            'is_access_require' => $this->is_access_require,
            'is_gas_life_support' => $this->is_gas_life_support,
            'is_any_unrestrained_animal' => $this->is_any_unrestrained_animal,
            'concession_card_type' => $this->concession_card_type,
            'concession_card_number' => $this->concession_card_number,
            'concession_start_date' => $this->concession_start_date,
            'concession_end_date' => $this->concession_end_date,
            'ea_go_neutral' => $this->ea_go_neutral,
            'additional_access_information' => $this->additional_access_information,
            'is_power_life_support' => $this->is_power_life_support,
//            'powershop_payment_info' => $this->mapPaymentInfo($this->powershopPaymentInfo),
            'powershop_payment_info' => $this->powershopPaymentInfo,
            'is_duplicate' => $this->is_duplicate,
            'duplication_group_id' => $this->duplication_group_id,
            'status_progress' => $this->mapStatusProgress(),
            'connection_services_status' => $this->mapConnectionServiceStatus($this->connectionServices, $this->tenancy_type, $this->state),
            'application_status' => $this->mapApplicationStatus(),
            'email_manually_verified_by' => $this->email_manually_verified_by,
            'is_generated_caf' => $this->is_generated_caf,
            'chatbot_id' => $this->chatbot_id,
        ];
    }

    private function getConnectionServices($services)
    {
        $service_array = [];
        $count = sizeof($services);
        for ($i = 0; $i < $count; $i++) {
            array_push($service_array, $services[$i]['service_type']);
        }

        return $service_array;
    }

    private function mapTsaService($callHistories)
    {
        $callHistoryArray = [];
        $count = sizeof($callHistories);
        for ($i = 0; $i < $count; $i++) {
            $callHistoryArray[$i]['attempt_outcome'] = $callHistories[$i]['attempt_outcome'];
            $callHistoryArray[$i]['attempt_initiated_timestamp'] = $callHistories[$i]['attempt_initiated_timestamp'];
            $callHistoryArray[$i]['attempt_id'] = $callHistories[$i]['attempt_id'];
        }
        return $callHistoryArray;
    }

    public function mapService($service)
    {

        try {


            if ($service) {
                $newService = [];

                foreach ($service as $svc) {
                    if (empty($svc->status)) {
                        $svc->status = ConnectionService::STATUS_UNASSIGNED;
                    }
                    $svc->statusText = ConnectionService::STATUS_MAPPING[$svc->status];
                    $newService[] = $svc;
                }
                return $newService;
            }
            return [];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return [];
        }
    }

//    private function getAgentName()
//    {
//        return match ($this->source) {
//            ConnectionApplication::SOURCE_HOOD => $this->createdBy?->first_name.' '. $this->createdBy?->last_name,
//            ConnectionApplication::SOURCE_FOXIE => $this->SugerLead?->agent_name,
//            ConnectionApplication::SOURCE_IGNITE => $this->igniteLead?->agent_name,
//            ConnectionApplication::SOURCE_OUR_PROPERTY => $this->ourPropertyLead?->agent_name,
//            ConnectionApplication::SOURCE_PROPERTY_ME => $this->propertyMeLead?->agent_name,
//            default => ''
//        };
//    }

//    private function getAgencyName()
//    {
//        return match ($this->source) {
//            ConnectionApplication::SOURCE_HOOD => $this->office?->name,
//            ConnectionApplication::SOURCE_FOXIE => $this->SugerLead?->agency_name,
//            ConnectionApplication::SOURCE_IGNITE => $this->igniteLead?->agency_name,
//            ConnectionApplication::SOURCE_OUR_PROPERTY => $this->ourPropertyLead?->agency_name,
//            ConnectionApplication::SOURCE_PROPERTY_ME => $this->propertyMeLead?->agency_name,
//            default => ''
//        };
//    }

    private function getAuthoizedPersonName()
    {

        if($this->authorizedPerson)
        {
            $fullName = "{$this->authorizedPerson->title} {$this->authorizedPerson->first_name} {$this->authorizedPerson->middle_name} {$this->authorizedPerson->last_name}";
            if(empty(trim($fullName))) return null;
            return $fullName;
        }
        return null;
    }

    private function mapPlan($plan): string
    {
        return 'bug';
        if (!empty($plan)) {
            return ConnectionApplication::PLAN_TYPE_REVERSE_MAPPER[$plan];
        }
        return 'total_plan';
    }

    private function submittedBy(){

        $fullName = $this->submittedByUser?->profile?->first_name .' '. $this->submittedByUser?->profile?->last_name;
        return trim($fullName);
    }

    private function submittedAt(){
        return $this->connectionServices?->pluck('submitted_at')?->sort()?->first();
    }

    /**
     * Getting Full form of STATE
     *
     * @param string|null $state
     *
     * @return string|null
     */
    private function getStateFull(?string $state): ?string
    {
        try {
            return StateMapService::getFullName($state);
        } catch (\Exception $e) {
            \Log::error("ApplicationResource " . $e->getMessage());
            return null;
        }
    }

//    private function mapPaymentInfo($info)
//    {
//       dd($info);
//       return false;
//    }


    /**
     * Getting Application Status for progress bar
     *
     * @return array|null
     */
    private function mapStatusProgress(): array | null
    {
        try {
            $service = new AgentStatusProgressMapper([
                'assignedTo' => $this->assigned_to,
                'applicationStatus' => $this->status,
                'applicationServices' => $this->connectionServices,
            ]);
            return $service->getAgentApplicationStatus();
        } catch (\Exception $e) {
            \Log::error("Error " . $e->getMessage());
            return null;
        }
    }


    /**
     * map connection service status
     *
     * @param $services
     * @return array|string[]|string[][]
     */
    public function mapConnectionServiceStatus($services, $tenancyType, $state):array
    {
        try {
            return (new AgentServiceApplicationStatusMapper())->getAgentServiceApplicationStatus($services, $tenancyType, $state);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return [];
        }
    }

    /**
     * Getting Application Status
     *
     *
     * @return string|null
     */
    private function mapApplicationStatus(): ?string
    {
        try {
            $service = new AgentStatusProgressMapper([
                'assignedTo' => $this->assigned_to,
                'applicationStatus' => $this->status,
                'applicationServices' => $this->connectionServices,
            ]);
            return $service->getApplicationStatus();
        } catch (\Exception $e) {
            \Log::error("Error " . $e->getMessage());
            return null;
        }
    }
}
