<?php
namespace App\Services\Agent;

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

    public function updateApplication() {

    }
}
