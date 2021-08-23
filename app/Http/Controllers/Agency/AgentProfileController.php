<?php


namespace App\Http\Controllers\Agency;


use App\Http\Requests\Agency\CreateAgentProfileRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\AgentProfileResource;
use App\Http\Resources\Agency\OfficeResource;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\SearchAgentProfileService;
use App\Services\Agency\SearchOfficeService;
use App\Services\Agency\UpdateAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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

    public function officeAgents(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $user = Auth::user();
            $officeId = $user->profile->office->id;
            $service = new SearchAgentProfileService($request->toArray());
            return AgentProfileResource::collection($service->get($officeId));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


    public function createAgent(CreateAgentProfileRequest $request)
    {
        try {
            $agentAndUserSvc = new CreateAgentAndUser();
            return AgencyResource::make($agentAndUserSvc->createAgentAndUser($request->toArray()));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }

    }

    public function getAgent(int $id)
    {
        try {
            $updateAgentService = new UpdateAgentService();
            return response()->json(['success' => false, 'message' => $updateAgentService->update($request->toArray(), $id)]);
//            return AgencyResource::make();
        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function updateAgent(Request $request, int $id)
    {
        try {
            $updateAgentService = new UpdateAgentService();
            return response()->json(['success' => false, 'message' => $updateAgentService->update($request->toArray(), $id)]);
//            return AgencyResource::make();
        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }

    }


}
