<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateOfficeRequest;
use App\Http\Requests\Agency\UpdateOfficeRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\ApplicationNoteResourse;
use App\Http\Resources\Agency\OfficeResource;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Services\Agency\AgencyService;
use App\Services\Agency\ApplicationNoteService;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agency\OfficeMatricsService;
use App\Services\Agency\OfficeService;
use App\Services\Agency\SearchOfficeService;
use App\Services\Agency\UpdateOfficeService;
use App\Services\MRI\HandleMRIOfficeService;
use App\Services\ReassignApplicationsServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;


class OfficeController extends Controller
{
    /**
     * Getting Office List for an agency
     *
     * @param Request $request
     * @param int $agencyId
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request, int $agencyId): AnonymousResourceCollection|JsonResponse
    {
        try {
            $service = new SearchOfficeService($request->toArray());
            return OfficeResource::collection($service->get($agencyId));

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Getting Office List for an agency
     *
     * @param Request $request
     * @param int $agencyId
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function allOffices(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $service = new SearchOfficeService($request->toArray());
            return OfficeResource::collection($service->get());

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Creating new Office inside Agency
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createOffice(CreateOfficeRequest $request)
    {
        try {
            $ofcAndAgencySvc = new CreateOfficeAndAgency($request->toArray());
            $agentAndUserSvc = new CreateAgentAndUser();
            $inputData       = $request->toArray();

            $officeData        = $inputData['office'];
            $agentData         = $inputData['agent'];
            $officeCommissions = $inputData['office_commissions'];
            $mriOffice         = $inputData['mri_office'];

            //create new office
            $office                 = $ofcAndAgencySvc->createOffice($officeData);
            $agentData['office_id'] = $office->id;
            $agentData['agency_id'] = $officeData['agency_id'];

            // Save MRI office
            if ($mriOffice) {
                $service = new HandleMRIOfficeService($office->id);
                $service->saveMRIOffice($mriOffice);
            }

            //create agent and user
            $agent                   = $agentAndUserSvc->createAgent($agentData);
            $agentData['profile_id'] = $agent->id;
            $user                    = $agentAndUserSvc->createUser($agentData);

            //create commission with data
            $commissions = $ofcAndAgencySvc->createCommistions($officeCommissions, $office->id, $officeData['agency_id']);
            return AgencyResource::make($office);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Creating new Office under Agency.
     *
     * @param CreateOfficeRequest $request
     * @param int $agencyId
     *
     * @return AgencyResource|JsonResponse
     */
    public function createAgencyOffice(CreateOfficeRequest $request, int $agencyId): AgencyResource|JsonResponse
    {
        try {
            $agencySvc = new AgencyService();
            $agency    = $agencySvc->getAgency($agencyId);

            $inputData                        = $request->toArray();
            $officeData                       = $inputData['office'];
            $officeData['agency_id']          = $agency->id;
            $officeCommissionsData            = $inputData['office_commissions'];
            $officeAllocatorData              = $inputData['agent'];
            $officeAllocatorData['agency_id'] = $agency->id;

            // Create service instances.
            $ofcAndAgencySvc = new CreateOfficeAndAgency();
            $agentAndUserSvc = new CreateAgentAndUser();

            // Create office.
            $office = $ofcAndAgencySvc->createOffice($officeData);

            // Create office allocator (agent) profile.
            $officeAllocatorData['office_id'] = $office->id;
            $agent                            = $agentAndUserSvc->createAgent($officeAllocatorData);

            // Create office commissions.
            $commissions = $ofcAndAgencySvc->createCommistions(
                $officeCommissionsData, $office->id, $officeData['agency_id']);

            return AgencyResource::make($office);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function updateOffice(UpdateOfficeRequest $request, int $id)
    {
        try {
            $service = new UpdateOfficeService($id);
            // ? TOOO why success false?
            return response()->json(['success' => false, 'message' => $service->updateOffice($request->toArray())]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getOffice(int $id)
    {

        try {
            $authUser = Auth::user();
            $office   = Office::find($id);
            if ($authUser->profile_type === AgentProfile::class &&
                $authUser->profile->agency_id !== $office->agency_id) {
                return $this->sendUnauthorizedResponse();
            }

            $service = new OfficeService($id);
            return response()->json(['success' => true, 'data' => $service->getOffice()]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getOnlyOffice(int $id)
    {
        try {
            $service = new OfficeService($id);
            return response()->json(['success' => true, 'data' => $service->getOnlyOffice()]);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getMatricsData(int $id)
    {
        try {
            $service = new OfficeMatricsService($id);
            $data    = $service->get();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Getting Office List for an agency
     *
     * @param Request $request
     * @param int $agencyId
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getOfficesForAssignApplications(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $service = new SearchOfficeService($request->toArray());
            return OfficeResource::collection($service->getOfficesForAssignApp());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function assignApplications(Request $request): JsonResponse
    {
        try {
            $service = new ReassignApplicationsServices($request->toArray());
            return response()->json($service->saveAssignedApplications());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
