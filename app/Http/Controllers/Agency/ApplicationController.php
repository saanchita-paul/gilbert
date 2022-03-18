<?php

namespace App\Http\Controllers\Agency;

use App\Events\Agency\CreateApplicationEvent;
use App\Events\Agency\SubmitApplicationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\ApplicationRequest;
use App\Http\Requests\Agency\ProviderRequest;
use App\Http\Resources\Agency\ApplicationMetricsResource;
use App\Http\Resources\Agency\ApplicationResource;
use App\Jobs\UpdateHubspotContactJob;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\User;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\WaterAutoSubmitService;
use App\Services\Application\ApplicationsMetricsService;
use App\Services\Application\SearchConnectionApplication;
use App\Services\FastConnectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use PropertyMe\services\FetchContacts;

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

    public function SearchConnectionApplicationAgents(Request $request): AnonymousResourceCollection|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->getApplicationForAgency($user));

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
        $application = $service->createApplication($request->toArray(), $user);
        CreateApplicationEvent::dispatch($application->id);

        return ApplicationResource::make($application);

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

            // todo refactor move to code to helper methed

            $authUser = Auth::user();
            if($authUser->profile_type === AgentProfile::class &&
                $authUser->profile->agency_id !== $application->agency_id) {
                return $this->sendUnauthorizedResponse();
            }

            $application->load(['connectionServices.reasons']);
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
            $data = $service->assignUser(
                $request->get('hood_user_id'),
                $applicationId
            );

            UpdateHubspotContactJob::dispatch($applicationId);

            $autoSubmitService = new WaterAutoSubmitService($applicationId);
            return ApplicationResource::make($data);

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
     */
    public function updateAddress(Request $request, int $applicationId)
    {
        try {
            $inputData = $request->get('address');
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->searchAddress($inputData);
            $service = new ApplicationService();

            return ApplicationResource::make($service->updateAddress(array_merge($inputData, $result), $applicationId));
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
        $requestArray = $request->toArray();

        $res = $service->submit($requestArray, $id);
        $authUser = Auth::user();

        $ea_services_id = $service->getNotSubmittedEaService($id);

        $options = ['auth_user'=>$authUser, 'services_id'=> $ea_services_id];

        SubmitApplicationEvent::dispatch($id, data_get($requestArray, 'lead.submit_type'), $options);

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
     * Updating status to escalate of an application
     *
     * @param Request $request
     * @param int $applicationId
     *
     * @return ApplicationResource|JsonResponse
     */
    public function close(Request $request, int $applicationId): ApplicationResource|JsonResponse
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

        /** @var User  $user */
        $user = auth()->user();
        $agencyId = (int) $request->get('agency_id');

        if ($agencyId && $user->profile_type === AgentProfile::class && $user->profile->agency_id !== $agencyId) {
            return $this->sendUnauthorizedResponse();
        }


        try {
            $user = auth()->user();
            $service = new ConnectionService();
            $inputData = $request->toArray();
            $data= $service->allApplicationMetricsCount($inputData, $user);
            return ApplicationMetricsResource::make($data);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function saveDraft(Request $request, $id)
    {
        try {
            $service = new ApplicationService();
            $res = $service->updateSoleField($request->toArray(), $id);
            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getNmiMern(Request $request, $id)
    {
        try {
            $service = new FastConnectService();
            $res = $service->authenticate()->searchAddress([], true, $id);
            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAuthorizedPerson($id)
    {
        try {
            $service = new ApplicationService();
            $res = $service->getAuthrisedInfo($id);
            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function updateAuthorizedPerson(Request $request)
    {
        try {
            $service = new ApplicationService();
            $inputData = $request->toArray();
            $res = $service->updateAuthrisedInfo($inputData);
            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function closeApplication(Request $request, $id)
    {
        try {
            $service = new ApplicationService();
            $user = Auth::user();
            $res = $service->closeApplicationWithReason($request->toArray(), $id, $user);
            return response()->json(['success' => true, 'data' => $res]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function updateService(Request $request , $application_id){
        try {
            $service = new ApplicationService();
            $data = $request->all();
            $data['connection_application_id'] = $application_id;

            $new_service = $service->updateService($data);

            if($new_service->wasRecentlyCreated) return response(['status' => true ,
                "message" => "Service created successfully",
                'service'=> $new_service] , 201);
            else return response(['status' => true ,
                "message" => "Service id: {$data['id']} updated successfully", 'service'=> $new_service] , 200);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function providers(ProviderRequest $request , $applicationId){
        try {
            $service = new ApplicationService();
            $inputData = $request->toArray();
            $service->providers($inputData, $applicationId);
            return response()->json(['success' => true, 'message' => 'providers updated successfully']);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAssignedHoodUser($applicationId){
        try {
            $service = new ApplicationService();
            $res = $service->getAssignedHoodUser($applicationId);
            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
