<?php
namespace App\Services\Agency;

use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;

class ApplicationService
{
    public function createApplication(array $application, User $user) {

        $agentProfile = $user->profile;
        $application['office_id'] = $agentProfile->office_id;
        $application['agency_id'] = $agentProfile->agency_id;
        $application['created_by'] = $agentProfile->id;
//        $application['assigned_to'] = $agentProfile->id;

        /** @var $newApplication ConnectionApplication */
        $newApplication = ConnectionApplication::create($application);
        $connectionService = [];

        foreach ($application['service_interests'] as $service) {
            $connectionService[] = ConnectionService::create(['service_type'=>$service['service_type'], 'connection_application_id'=>$newApplication->id]);
        }

        return $newApplication;

    }

    public function getNotes($application_id)
    {
        return ApplicationNote::query()->where('connection_application_id','=', $application_id)->get();
    }

    public function createNotes(array $note, User $user, $applicationId) {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $user->id;
        return ApplicationNote::create($note);
    }

}
