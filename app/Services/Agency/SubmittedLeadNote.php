<?php


namespace App\Services\Agency;


use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Powershop\Services\SameDayConnectionService;
use App\Services\Ea\EaPlanDetailsService;
use App\Services\Utility\StateMapService;
use Origin\Services\OriginPlanDetailsService;
use Origin\Services\ValidateCutOffTime;
use phpDocumentor\Reflection\Utils;
use Powershop\Services\PowershopPlanDetailsService;

class SubmittedLeadNote
{

    public function __construct(public ConnectionApplication $existLead, public $user, public array $servicesId)
    {

    }

   private function prepareLeadData($planType, $postCode, $state, $submittedService, $provider_name = 'N/A', $gas_plan_type = null)
    {
       $leadData = [
           'utility_type' => $submittedService,
           'address_text' => $this->existLead?->address_text,
           'application_name' => $this->existLead?->first_name . ' ' . $this->existLead?->last_name ,
           'moving_date' => $this->existLead?->moving_date,
           'is_contacted' => $this->existLead && $provider_name === 'EA'? ($this->existLead->is_contacted ? 'Yes': 'No') : 'N/A',
           'source' => ConnectionApplication::SOURCE_NAME_MAPPING[$this->existLead?->source],
           'agency' => $this->existLead->getAgencyName(),
           'agent_name' => $this->existLead?->getAgentName(),
           'nmi' => $this->existLead?->nmi,
           'mirn' => $this->existLead?->mirn,
           'supplier' => $provider_name,
           'plan_type' => $planType,
           'post_code' => $postCode,
           'state' => $state,
           'services' => $submittedService,
           'first_name' => $this->existLead?->first_name,
           'last_name' => $this->existLead?->last_name,
           'application_id' => $this->existLead?->id,
           'ea_go_neutral' => $this->existLead && $provider_name === 'EA'? $this->existLead->ea_go_neutral : 'N/A',
        ];

        if ($this->existLead) {
            $leadData['source'] = $this->existLead->source_name;
        }

        if ($provider_name == 'Powershop' && in_array($submittedService, ['Elec & Gas'])){
            $sameDayService = new SameDayConnectionService($this->existLead->id, $this->getSubmitType($submittedService));
            $leadData['gas_moving_date'] = $sameDayService->getNextGasConnectionDate();
            if (!empty($gas_plan_type)) $leadData['gas_plan_type'] = $gas_plan_type;
        }

        if ($provider_name == 'Origin' && in_array($submittedService, ['Gas', 'Elec & Gas'])){
            if ($submittedService === 'Gas'){
                $leadData['moving_date'] = ValidateCutOffTime::getNextGasConnectionDate($this->existLead?->moving_date, $this->existLead?->state);
            }
            else if ($submittedService === 'Elec & Gas'){
                $leadData['gas_moving_date'] = ValidateCutOffTime::getNextGasConnectionDate($this->existLead?->moving_date, $this->existLead?->state);
            }
        }

       return json_encode($leadData);
    }

    public function addSubmittedNote()
    {
        $stateService = new StateMapService();
        $state = $stateService->getShortName($this->existLead?->state);
        $postCode = $this->existLead?->postcode;

        $this->doSubmitEaNote($state, $postCode);
        $this->doSubmitOriginNote($state, $postCode);
        $this->doSubmitPowershopNote($state, $postCode);
    }

    private function doSubmitEaNote($state, $postCode){
        $eaPlanService = new EaPlanDetailsService($state, $postCode, $this->existLead->id, $this->servicesId);
        $plan_type = $eaPlanService->plan_type;

        //if provider is not ea then note is not created
        if(empty($plan_type)) return;

//        $user = \Auth::user();
        $noteService = new ApplicationNoteService($this->user);
        $submittedService = $this->getServices($eaPlanService->service_type);
        $planDetails = $eaPlanService->getPlanDetails();
        $this->leadDetailsJson = $this->prepareLeadData($plan_type, $postCode, $state, $submittedService, 'EA');
        $note = [
            'type' => ApplicationNote::SUBMITTED_CONNECTION,
            'connection_details' => $this->leadDetailsJson,
            'plan_details' => $planDetails
        ];
        $noteService->createNotes($note, $this->existLead?->id);
    }

    private function doSubmitPowershopNote($state, $postCode){
        $powershopPlanService = new PowershopPlanDetailsService($state, $postCode, $this->existLead->id, $this->servicesId, $this->existLead->nmi ?? '');

        $elec_plan_type = $powershopPlanService->elec_plan_type;
        $gas_plan_type = $powershopPlanService->gas_plan_type;

        if(empty($elec_plan_type)) return;

        $noteService = new ApplicationNoteService($this->user);
        $submittedService = $this->getServices($powershopPlanService->service_type);
        $planDetails = $powershopPlanService->getPlanDetails();
        $this->leadDetailsJson = $this->prepareLeadData($elec_plan_type, $postCode, $state, $submittedService, 'Powershop', $gas_plan_type ?? null);
        $note = [
            'type' => ApplicationNote::SUBMITTED_POWERSHOP,
            'connection_details' => $this->leadDetailsJson,
            'plan_details' => $planDetails
        ];
        $noteService->createNotes($note, $this->existLead?->id);
    }

    private function doSubmitOriginNote($state, $postCode){
        // \Log::info("debugging the note", [$state, $postCode]);
        $originPlanService = new OriginPlanDetailsService($state, $postCode, $this->existLead->id, $this->servicesId, $this->existLead->nmi ?? '');
        $plan_type = $originPlanService->plan_type;

        if(empty($plan_type)) return;

        $noteService = new ApplicationNoteService($this->user);
        $submittedService = $this->getServices($originPlanService->service_type);
        $planDetails = $originPlanService->getPlanDetails();
        // TODO: get gas connection date
        $this->leadDetailsJson = $this->prepareLeadData($plan_type, $postCode, $state, $submittedService, 'Origin');
        $note = [
            'type' => ApplicationNote::SUBMITTED_ORIGIN,
            'connection_details' => $this->leadDetailsJson,
            'plan_details' => $planDetails
        ];
        $noteService->createNotes($note, $this->existLead?->id);
    }

    private function getServices($serviceType)
    {
       return match ($serviceType) {
          ConnectionService::TYPE_GAS => 'Gas' ,
          'electricity' => 'Elec' ,
          'electricity_and_gas' => 'Elec & Gas',
        };
    }

    private function getSubmitType($serviceType)
    {
        return match ($serviceType) {
            ConnectionService::TYPE_GAS, 'Gas' => ConnectionService::TYPE_GAS ,
            'electricity', 'Elec' => ConnectionService::TYPE_ELECTRICITY,
            'electricity_and_gas', 'Elec & Gas' => 'energy',
          };
    }

}
