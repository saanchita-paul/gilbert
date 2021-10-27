<?php
namespace App\Http\Controllers\Agency;

use App\Models\AgentProfile;
use App\Services\Agency\AgencyUserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SendUserInviteService;
use App\Services\UpdateUserProfileService;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\UpdateAgentService;
use App\Http\Resources\Agency\AgencyResource;
use App\Services\Agency\SearchAgentProfileService;
use App\Http\Resources\Agency\AgentProfileResource;
use App\Http\Requests\Agency\CreateAgentProfileRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgentProfileController extends Controller
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
            return $this->sendErrorResponse($exception);
        }
    }

    public function officeAgents(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $user = Auth::user();
            $officeId = $user->profile->office?->id;
            $service = new SearchAgentProfileService($request->toArray());
            return AgentProfileResource::collection($service->get($officeId));

        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }


    public function createAgent(CreateAgentProfileRequest $request)
    {
        try {
            $agentAndUserSvc = new CreateAgentAndUser();
            $res = $agentAndUserSvc->createAgentAndUser($request->toArray());

            (new SendUserInviteService($res->user))->run();

            return AgencyResource::make($res);

        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }

    public function getAgent(int $id)
    {
        try {
            $updateAgentService = new UpdateAgentService();
            return response()->json(['success' => false, 'message' => $updateAgentService->update($id)]);
//            return AgencyResource::make();
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function updateProfile(Request $request, int $id)
    {
        try {
            $updateAgentService = new UpdateUserProfileService($id);
            return response()->json(['success' => false, 'user' => $updateAgentService->updateProfile($request->toArray())]);
//            return AgencyResource::make();
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }

    public function updateUserData(Request $request , $id)
    {
        try {
            $updateAgentService = new UpdateUserProfileService($id);
            return response()->json(['success' => false, 'user' => $updateAgentService->updateUserData($request->toArray())]);
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function sendConfirmMail($id)
    {
        try {
            $userService = new AgencyUserService();
            (new SendUserInviteService($userService->getUserByProfile($id)))->run();
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

}
