<?php
namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAgentProfileRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\AgentListResource;
use App\Http\Resources\Agency\AgentProfileResource;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Services\Agency\AgencyUserService;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\SearchAgentProfileService;
use App\Services\Agency\UpdateAgentService;
use App\Services\SendUserInviteService;
use App\Services\UpdateUserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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

            if(env('SEND_AGENT_CREATE_EMAIL', 0)) {
                (new SendUserInviteService($res->user))->run();
            }
            return AgencyResource::make($res);

        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }

    public function getAgent(int $id)
    {
        try {
            $updateAgentService = new UpdateAgentService();
            return response()->json(['success' => true, 'message' => $updateAgentService->update($id)]);
//            return AgencyResource::make();
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function updateProfile(Request $request, int $id)
    {
        try {
            $updateAgentService = new UpdateUserProfileService($id);
            return response()->json(['success' => true, 'user' => $updateAgentService->updateProfile($request->toArray())]);
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Update Agency User
     *
     * @param Request $request
     * @param int $agenProfileId
     *
     * @return JsonResponse
     */
    public function updateUserData(Request $request , $id){
        try {
            $updateAgentService = new UpdateUserProfileService($id);
            return response()->json(['success' => true, 'user' => $updateAgentService->updateUserData($request->toArray())]);
        } catch ( \Exception $exception) {
            return response( $exception->getMessage() , 409);
        }
    }

    public function sendConfirmMail(Request $request, $id)
    {
        try {
            $userService = new AgencyUserService();
            (new SendUserInviteService($userService->getUserByProfile($request->toArray(), $id)))->run();
            return response()->json(['success' => true, ]);
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAgentList(Request $request, int $officeId): AnonymousResourceCollection | JsonResponse
    {
        try {

            $authUser = Auth::user();
            $office = Office::find($officeId);
            if($authUser->profile_type === AgentProfile::class &&
                $authUser->profile->agency_id !== $office->agency_id) {
                return $this->sendUnauthorizedResponse();
            }


            $service = new SearchAgentProfileService($request->toArray());
            return AgentListResource::collection($service->getAgentList($officeId));
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

}
