<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ApplicationRequest;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Services\Agent\AgentProfileService;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{

    /**
     * Getting Application list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */

    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->get($user));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


    /**
     * Create new application
     *
     * @param ApplicationRequest $request
     *
     */
    public function create(ApplicationRequest $request)
    {
        try{
            /** @var  User $user */
            $user = Auth::user();

            $service = new ApplicationService();
            $inputData = $request->toArray();
            return ApplicationResource::make($service->createApplication($inputData, $user));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**agencyId
     * Getting Agency list
     *
     * @param Request $request
     *
     * @return ApplicationResource|JsonResponse
     */
    public function summary(Request $request, ConnectionApplication $application): ApplicationResource | JsonResponse
    {
        try {
            $application->load(['connectionServices']);
            return new ApplicationResource($application);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Updating assignee of an application
     *
     * @param Request $request
     * @param int $applicationId
     *
     * @return ApplicationResource|JsonResponse
     */
    public function updateAssignee(Request $request, int $applicationId): ApplicationResource | JsonResponse
    {
        try {
            $service = new ApplicationService();
            $inputData = $request->toArray();
            return ApplicationResource::make($service->updateApplication($inputData, $applicationId));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Updating status to escalate of an application
     *
     * @param Request $request
     * @param int $applicationId
     *
     * @return ApplicationResource|JsonResponse
     */
    public function updateEscalate(Request $request, int $applicationId): ApplicationResource | JsonResponse
    {
        try {
            $service = new ApplicationService();
            $inputData = $request->toArray();
            return ApplicationResource::make($service->updateEscalateApplication($inputData, $applicationId));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
