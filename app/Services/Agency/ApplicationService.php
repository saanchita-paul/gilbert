<?php
namespace App\Services\Agency;

use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;
use Illuminate\Console\Application;

class ApplicationService
{
    public function createApplication(array $application, User $user){

        $agentProfile = $user->profile;
        $application['office_id'] = $agentProfile->office_id;
        $application['agency_id'] = $agentProfile->agency_id;
        $application['created_by'] = $agentProfile->id;
//        $application['assigned_to'] = $agentProfile->id;

        /** @var $newApplication ConnectionApplication */
        $newApplication = ConnectionApplication::create($application);
        $connectionService = [];

        foreach ($application['service_interests'] as $service) {
            $connectionService[] = ConnectionService::create(
                ['service_type'=>$service['service_type'],
                'connection_application_id'=>$newApplication->id]
            );
        }

        return $newApplication;

    }


    public function getNotes($application_id)
    {
        return ApplicationNote::query()->where('connection_application_id','=', $application_id)->get();
    }

    public function createNotes(array $note, User $user, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $user->id;
        if($note['title'] == null) {
            $note['title'] = 'Note by '.$user->profile->first_name;
        }
        return ApplicationNote::create($note);
    }
    public function updateApplication(array $application, int $applicationId) {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->assigned_to = $application['agent_profile_id'];
        $existingApplication->save();

        return $existingApplication;

    }

    public function updateConnectionService(array $serviceList, $id)
    {
        ConnectionService::query()->where('connection_application_id', '=', $id)->delete();
        foreach ($serviceList as $service) {
            ConnectionService::create(['service_type'=>$service, 'connection_application_id'=>$id]);
        }
    }

    public function createIdentification($identificationData, $id)
    {
        $identification = Identification::where('connection_application_id' , $id);
        if($identification)
        {
           return $identification->update($identificationData);
        }
        $identificationData['connection_application_id'] = $id;
        return Identification::create($identificationData);
        
    }


    public function reCreateLead(array $applications, $id, $user) {

        $applications['office_id'] = $user->office_id;
        $applications['agency_id'] = $user->agency_id;
        $lead = $applications['lead'];
        $lead['office_id'] = $user->office_id;;
        $lead['agency_id'] = $user->agency_id;;
        $existLead = ConnectionApplication::findOrFail($id);
        $existLead->update($lead);
        $this->createIdentification($lead['identification'], $id);
        $this->updateConnectionService($lead['service_interests'], $id);
        return $existLead;
    }


    public function updateEscalateApplication(array $application, int $applicationId, User $user) {

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
}
