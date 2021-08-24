<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\ApplicationRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\ApplicationNoteResourse;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Models\User;
use App\Services\Agency\ApplicationNoteService;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\ApplicationsMetricsService;
use App\Services\Agency\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{

    /**
     * Getting Applications list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->get($user));

        } catch (\Exception $exception) {
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
        try {
            /** @var  User $user */
            $user = Auth::user();

            $service = new ApplicationService();
            $inputData = $request->toArray();
            return ApplicationResource::make($service->createApplication($inputData, $user));

        } catch (\Exception $exception) {
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
    public function view(Request $request, ConnectionApplication $application): ApplicationResource|JsonResponse
    {
        try {
            $application->load(['connectionServices']);
            return new ApplicationResource($application);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Getting Application Metrics
     *
     * @return JsonResponse
     */
    public function getMetrics(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $service = new ApplicationsMetricsService($user->profile_id, $user->profile?->office_id);
            return response()->json(['data' => $service->toArray()]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param ConnectionApplication $application
     * @return JsonResponse
     */
    public function getConnectionNotes(string $application)
    {
        try {
            $service = new ApplicationService();
            return ApplicationNoteResourse::collection($service->getNotes($application));
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function createConnectionNotes(Request $request, string $id): ApplicationNoteResourse|JsonResponse
    {
        try {
            $service = new ApplicationNoteService(Auth::user());
            return ApplicationNoteResourse::make($service->createNotes($request->toArray(), $id));
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

     /**
      * Assigning user to an Application
      *
      * @param Request $request
      * @param int $applicationId
      *
      * @return ApplicationResource|JsonResponse
      */
    public function assignUser(Request $request, int $applicationId): ApplicationResource|JsonResponse
    {
        try {
            $service = new ApplicationService();
            return ApplicationResource::make($service->assignUser(
                $request->get('hood_user_id'),
                $applicationId
            ));

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


    public function submit(Request $request, $id)
    {

        try {
            $service = new ApplicationService();
            $user = Auth::user();
            $profile = $user->profile;
            return ApplicationResource::make($service->reCreateLead($request->toArray(), $id, $profile));
        } catch (\Exception $exception) {
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
    public function escalate(Request $request, int $applicationId): ApplicationResource|JsonResponse
    {
        try {
            $service = new ApplicationService();
            $inputData = $request->toArray();
            $user = Auth::user();
            return ApplicationResource::make($service->updateEscalateApplication($inputData, $applicationId, $user));

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
