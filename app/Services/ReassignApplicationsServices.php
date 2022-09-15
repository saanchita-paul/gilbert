<?php

namespace App\Services;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\Office;
use Illuminate\Support\Facades\DB;

class ReassignApplicationsServices
{
    private array $reports = [];
    private int $totalSuccessCount = 0;

    public function __construct(private array $applications)
    {
    }

    public function saveAssignedApplications()
    {
        foreach ($this->applications as $application) {
            try {
                $connectionApplication = ConnectionApplication::find($application['id']);
                $office = Office::find($application['office_id']);
                $agent_profile = AgentProfile::find($application['created_by']);
                if ($connectionApplication && $office && $agent_profile) {
                    $connectionApplication->update($application);
                }
                $this->totalSuccessCount++;
                $this->reports[] = ['lead_id' => $connectionApplication->id, 'status' => 'Success!'];
            } catch (\Exception $exception) {
                $this->reports[] = ['lead_id' => $connectionApplication->id, 'status' => $exception->getMessage()];
            }
        }
        return [
            'message' => $this->getMessage(),
            'data' => $this->reports
        ];

    }


    private function getMessage()
    {
        $total = count($this->applications);
        return "Successfully $this->totalSuccessCount/$total application reassigned";
    }

}
