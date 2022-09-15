<?php

namespace App\Services;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\Office;
use Illuminate\Support\Facades\DB;

class ReassignApplicationsServices
{

    public function saveAssignedApplications(array $applications)
    {
        try {
            DB::beginTransaction();
            foreach ($applications as $application) {
                $connectionApplication = ConnectionApplication::find($application['id']);
                $office = Office::find($application['office_id']);
                $agent_profile = AgentProfile::find($application['created_by']);
                if ($connectionApplication && $office && $agent_profile) {
                    $connectionApplication->update($application);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
