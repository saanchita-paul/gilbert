<?php


namespace App\Http\Controllers\Agency;


use App\Http\Requests\Agency\CreateAgentProfileRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\AgentProfileResource;
use App\Http\Resources\Agency\OfficeResource;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\SearchAgentProfileService;
use App\Services\Agency\SearchOfficeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgentProfileController
{
    /**
     * Getting Agency User list an office
     *
     * @param Request $request
     * @param int $officeId
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request, int $officeId): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new SearchAgentProfileService($request->toArray());
            return AgentProfileResource::collection($service->get($officeId));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


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
