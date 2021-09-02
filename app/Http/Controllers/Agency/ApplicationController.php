<?php

namespace App\Http\Controllers\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\ApplicationRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\ApplicationMetricsResource;
use App\Http\Resources\Agency\ApplicationNoteResourse;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\User;
use App\Services\Agency\ApplicationNoteService;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\ApplicationsMetricsService;
use App\Services\Agency\HubspotContactService;
use App\Services\Agency\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
    return $this->sendErrorResponse($exception);
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
        return $this->sendErrorResponse($exception);
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
            return $this->sendErrorResponse($exception);
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
        return $this->sendErrorResponse($exception);
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
            return $this->sendErrorResponse($exception);
        }
    }


    /**
     * Submitting an Application
     *
     * @param Request $request
     * @param $id
     *
     * @return ApplicationResource|JsonResponse
     */
    public function submit(Request $request, $id)
{
    try {
        $service = new ApplicationService();
        $res = $service->submit($request->toArray(), $id);
        SubmitApplicationEvent::dispatch($id);

        $hubspotContactService = new HubspotContactService();
        $response = $hubspotContactService->submitContact($id);

        return ApplicationResource::make($res);
    } catch (\Exception $exception) {
        return $this->sendErrorResponse($exception);
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
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Getting All Aplication Metrics Count
     *
     * @param Request $request
     *
     */
    public function getApplicationMetricsCount(Request $request)
    {
        try {
            $user = auth()->user();
            $service = new ConnectionService();
            $inputData = $request->toArray();
            $service= $service->allApplicationMetricsCount($inputData, $user->profile->office_id);

            return ApplicationMetricsResource::make($service);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
