<?php


namespace App\Services\Agency;


use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Ea\EaPlanDetailsService;
use App\Services\Utility\StateMapService;
use phpDocumentor\Reflection\Utils;

class SubmittedLeadNote
{

    public function __construct(public ConnectionApplication $existLead)
    {

    }

   private function prepareLeadData($planType, $postCode, $state, $submittedService)
    {
       $leadData = [
           'utility_type' => $submittedService === ConnectionService::TYPE_GAS ?
                ucfirst(ConnectionService::TYPE_GAS) : ($submittedService === ConnectionService::TYPE_ELECTRICITY ?
                    'Elec' : 'Elec & Gas'),
           'address_text' => $this->existLead?->address_text,
           'application_name' => $this->existLead?->first_name . ' ' . $this->existLead?->last_name ,
           'moving_date' => $this->existLead?->moving_date,
           'is_contacted' => $this->existLead?->is_contacted ? 'Yes': 'No',
           'source' => ConnectionApplication::SOURCE_NAME_MAPPING[$this->existLead?->source],
           'agency' => $this->existLead->getAgencyName(),
           'agent_name' => $this->existLead?->getAgentName(),
           'nmi' => $this->existLead?->nmi,
           'mirn' => $this->existLead?->mirn,
           'supplier' => 'EA',
           'plan_type' => $planType,
           'post_code' => $postCode,
           'state' => $state,
           'services' => $submittedService,
           'first_name' => $this->existLead?->first_name,
           'last_name' => $this->existLead?->last_name,
           'application_id' => $this->existLead?->id
        ];

       return json_encode($leadData);
    }

    public function addSubmittedNote()
    {
        $stateService = new StateMapService();
        $state = $stateService->getShortName($this->existLead?->state);
        $postCode = $this->existLead?->postcode;
        $eaPlanService = new EaPlanDetailsService($state, $postCode, $this->existLead->id);
        $plan_type = $eaPlanService->plan_type;

        //if provider is not ea then note is not created
        if(empty($plan_type)) return;

        $user = \Auth::user();
        $noteService = new ApplicationNoteService($user);
        $submittedService = $this->getServices($eaPlanService->service_type);
        $planDetails = $eaPlanService->getPlanDetails();
        $this->leadDetailsJson = $this->prepareLeadData($plan_type, $postCode, $state, $submittedService);
        $note = [
            'type' => ApplicationNote::SUBMITTED_CONNECTION,
            'connection_details' => $this->leadDetailsJson,
            'plan_details' => $planDetails
        ];
        $noteService->createNotes($note, $this->existLead?->id);
    }

    private function getServices($serviceType)
    {
       return match ($serviceType) {
          ConnectionService::TYPE_GAS => 'gas' ,
          'electricity' => 'electricity' ,
          'electricity_and_gas' => 'Electricity & Gas',
        };
    }


}
