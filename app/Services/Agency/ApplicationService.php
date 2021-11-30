<?php

namespace App\Services\Agency;

use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Sales\PostSalesService;
use Illuminate\Console\Application;
use function PHPUnit\Framework\isNull;

class ApplicationService
{
    /**
     * @param array $application
     * @param User $user
     * @return ConnectionApplication
     */
    public function createApplication(array $application, User $user)
    {

        $agentProfile = $user->profile;
        $application['office_id'] = $agentProfile->office_id;
        $application['agency_id'] = $agentProfile->agency_id;
        $application['created_by'] = $agentProfile->id;
        $application['status'] = ConnectionApplication::STATUS_UNASSIGNED;
        $authizedPerson = $application['authorized_person'];


        /** @var $newApplication ConnectionApplication */
        $newApplication = ConnectionApplication::create($application);
        $connectionService = [];


        $this->createApplicationSerive($newApplication->id, $application['service_interests']);

//        foreach ($application['service_interests'] as $service) {
//            $connectionService[] = ConnectionService::create(
//                ['service_type' => $service['service_type'],
//                    'connection_application_id' => $newApplication->id]
//            );
//        }

        if(!empty($authizedPerson))
        {
            $authizedPerson['connection_application_id'] = $newApplication->id;
        }
        ConnectionApplicationSecondaryACC::create($authizedPerson);
        $this->createIdentification($application['identification'], $newApplication->id);

        return $newApplication;

    }


    public function getNotes($application_id)
    {
        return ApplicationNote::query()
            ->where('connection_application_id', '=', $application_id)
            ->orderBy('created_at','desc')
            ->get();
    }

    public function createNotes(array $note, User $user, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $user->id;
        if ($note['title'] == null) {
            $note['title'] = 'Note by ' . $user->profile->first_name;
        }
        return ApplicationNote::create($note);
    }

    public function updateApplication(array $application, int $applicationId)
    {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->assigned_to = $application['agent_profile_id'];
        $existingApplication->status = ConnectionApplication::STATUS_SUBMITTED;
        $existingApplication->save();
    }

    public function updateAddress(array $address, int $applicationId)
    {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->address_text = $address['address_text'];
        $existingApplication->street_address = $address['street_address'];
        $existingApplication->street_name = $address['street_name'];
        $existingApplication->street_number = empty($address['street_address']) ? null : $address['street_number'];
        $existingApplication->unit_number = empty($address['street_address']) ? null : $address['unit_number'];
        $existingApplication->city = $address['city'];
        $existingApplication->is_renovation_on = $address['is_renovation_on'];
        $existingApplication->has_electricity = $address['has_electricity'];
        $existingApplication->inspection_time = $address['inspection_time'];
        $existingApplication->postcode = $address['postcode'];
        $existingApplication->state = $address['state'];
        $existingApplication->country = $address['country'];
        $existingApplication->mirn = $address['mirn'];
        $existingApplication->nmi = $address['nmi'] ;
//        $existingApplication->is_billing_same = $address['is_billing_same'];


        if (!$address['is_billing_same']) {
            $existingApplication->billing_address_text = $address['billing_address_text'];
            $existingApplication->billing_street_address = $address['billing_street_address'];
            $existingApplication->billing_street_name = $address['billing_street_name'];
            $existingApplication->billing_street_number = empty($address['billing_street_address']) ? null : $address['billing_street_number'];
            $existingApplication->billing_city = empty($address['billing_city']) ? null : $address['billing_city'];
            $existingApplication->billing_postcode = empty($address['billing_postcode']) ? null : $address['billing_postcode'];
////            $existingApplication->billing_state = empty($address['billing_state']) ? null : $address['billing_state'] ;
//            $existingApplication->billing_country = empty($address['billing_country']) ? null : $address['billing_country'] ;
        }
        $existingApplication->save();

        return $existingApplication;
    }

    public function assignUser(string $agentId, int $applicationId)
    {
        ConnectionApplication::query()
            ->where('id', $applicationId)
            ->update(['assigned_to' => $agentId, 'status' => ConnectionApplication::STATUS_ASSIGNED]);

        return $this->findApplications($applicationId);
    }


    public function findApplications(int $id)
    {
        return ConnectionApplication::query()
            ->where('id', $id)
            ->with('connectionServices', 'identification')
            ->first();
    }

    public function updateConnectionService(array $serviceList, $id)
    {
        ConnectionService::query()->where('connection_application_id', '=', $id)
            ->whereIn('service_type',[ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->delete();
        foreach ($serviceList as $service) {
            ConnectionService::create(
                [
                    'service_type' => $service,
                    'connection_application_id' => $id,
                    'status' => ConnectionService::WATER_STATUS_IN_PROGRESS
                ]
            );
        }
    }

    public function createIdentification($identificationData, $id)
    {


        \Log::info('identification data_'.$id, $identificationData);
        $identification = Identification::where('connection_application_id', $id)->firstOrFail();
        if(isset($identificationData['medicare_expire_date'])) {
            unset($identificationData['medicare_expire_date']);
        }
        
        if ($identification) {
            $identification->update($identificationData);
        
        } else{
            $identificationData['connection_application_id'] = $id;
            return Identification::create($identificationData);
        }


    }


    public function submit(array $applications, $id)
    {

        $lead = $applications['lead'];
        $vendorId = $this->calculateVendorId($id);
        $lead = array_merge($lead, [
            'plan_type' => null,
            'assigned_to' => null,
            'status' => ConnectionApplication::STATUS_SUBMITTED,
            'submitted_by' => auth()->id(),
            'vendor_id' => $vendorId
        ]);

        $existLead = ConnectionApplication::findOrFail($id);

        $existLead->update($lead);

        $this->createIdentification($lead['identification'], $id);

//      $this->updateConnectionService($lead['service_interests'], $id);

        $this->checkAndAddWaterService($applications , $existLead);

        return $existLead;
    }

    private function checkAndAddWaterService(array $applications , $application){
        info("printing application data");
        \Log::info($applications);
        \Log::info($applications['lead']['service_interests']);
        // if( array_search("water",$applications['lead']['service_interests'])){
        //     info("water found");
        // };
        if( $applications['lead']['submit_type'] == 'water'){
            info("water found");

            $waterService =  ConnectionService::where("connection_application_id" , $application->id)->where( "service_type" ,  "water")->first();
            if(!$waterService){
                info('creating water...');
                $application->connectionServices()->create(['service_type' => 'water']);
            }else{
                info('water found inside');
                \Log::info($waterService);
            }

        };





    }


    public function updateEscalateApplication(array $application, int $applicationId, User $user)
    {

        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->reason = $application['reason'];
//        $existingApplication->status = ConnectionApplication::STATUS_MAPPING[$application['status']];
        $existingApplication->status = ConnectionApplication::STATUS_ESCALATED;
        $existingApplication->save();

        $allicationNoteService = new ApplicationNoteService($user);
        $eacalateNote = [];
        $eacalateNote['text'] = $application['reason'];
        $eacalateNote['type'] = 'Escalated';

        $allicationNoteService->createNotes($eacalateNote, $applicationId);

        return $existingApplication;
    }

    /**
     * 
     * @param array $application
     * @param int $applicationId
     * @param User $user
     * @return ConnectionApplication $existingApplication
     */
    public function closeApplicationWithReason(array $application, int $applicationId, User $user)
    {
        try {
            $existingApplication = ConnectionApplication::find($applicationId);
            $existingApplication->closing_reason = $application['closing_reason'];
            $existingApplication->status = ConnectionApplication::STATUS_CLOSED;
            $existingApplication->save();


            $allicationNoteService = new ApplicationNoteService($user);
            $closingeNote = [];
            $closingeNote['text'] = $application['closing_reason'];
            $closingeNote['type'] = 'close_connection';

            $allicationNoteService->createNotes($closingeNote, $applicationId);


            return $existingApplication;
        } catch (\Exception $exception) {
            \Log::error("**CloseApplication**", 
            ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
        }
    }

    public function updateSoleField(array $application, $id)
    {
        $existLead = ConnectionApplication::findOrFail($id);
        $isIdentification = $application['identification'];
        $isService = $application['isService'];

        unset($application['identification']);
        unset($application['isService']);

        if ($isIdentification) {
            $this->createIdentification($application, $id);
        } else if ($isService) {
            $this->updateConnectionService($application['service_types'], $id);
        } else {

            if(isset($application['plan_type']) && isset($application['plan_type']['key']))
            {
                $application['plan_type'] = ConnectionApplication::PLAN_TYPE_MAPPER[$application['plan_type']['key']];
            }

            $existLead->update($application);
        }

        return $existLead->refresh();
    }

    public function getAuthrisedInfo($id)
    {
        return ConnectionApplicationSecondaryACC::query()
            ->where('connection_application_id','=', $id)
            ->first();
    }

    public function updateAuthrisedInfo($data)
    {
        $id = $data['id'];
        if(!empty($id))
        {
            $authorizedPerson =  ConnectionApplicationSecondaryACC::find($id);
            $authorizedPerson->update($data);
            return $authorizedPerson->refresh();
        } else
        {
            $authorizedPerson =  ConnectionApplicationSecondaryACC::create($data);
            return $authorizedPerson;
        }

    }

    private function calculateVendorId($id)
    {
        return 'HD2_CRM'.str_pad($id, 10, "0", STR_PAD_LEFT);;
    }

    public function closeApplication($id)
    {
        $connectionApplication =  ConnectionApplication::find($id);
        $connectionApplication->update(['status' => 8]);
        return $connectionApplication->refresh();
    }

    public function createApplicationSerive($id, $services):void
    {
        foreach ($services as $service) {
            $connectionService[] = ConnectionService::create(
                [
                    'service_type' => $service['service_type'],
                    'connection_application_id' => $id,
                    'status'=> ConnectionService::WATER_STATUS_IN_PROGRESS
                ]
            );
        }
    }

    public function updateService(array $data){

        try {
            return ConnectionService::updateOrCreate(
                [ 'id' => $data['id'] ?? null ],
                $data
            );
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function providers(array $data, $applicationId)
    {
        $connectionApplication =  ConnectionApplication::find($applicationId);
        foreach ($data['service_type'] as $service) {
            $connectionService = ConnectionService::where('connection_application_id', $applicationId)
                ->where('service_type', $service)
                ->first();
            if( $connectionService ) {
                $connectionService->provider_name = $data['provider_name'];
                $connectionService->plan_type = $data['plan_type'];
                $connectionService->save();
            }
        }
    }


}
