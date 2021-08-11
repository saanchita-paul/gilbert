<?php


namespace App\Http\Controllers\Agency;


use App\Http\Requests\Agency\CreateAgentProfileRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\SearchOfficeService;

class AgentProfileController
{
    public function createAgent(CreateAgentProfileRequest $request)
    {
        try {
            $inputData = $request->toArray();
            $agentAndUserSvc = new CreateAgentAndUser();
            $searchOfficeSvc = new SearchOfficeService([]);

            $office = $searchOfficeSvc->getOffice($inputData['office_id']);
            $inputData['agency_id'] = $office->agency_id;

            $agent = $agentAndUserSvc->createAgent($inputData);
            $inputData['profile_id'] = $agent->id;
            $user = $agentAndUserSvc->createUser($inputData);
            return AgencyResource::make($agent);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }


    }
}
