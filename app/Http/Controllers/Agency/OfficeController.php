<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateOfficeRequest;
use App\Http\Requests\Agency\UpdateOfficeRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\OfficeResource;
use App\Services\Agency\AgencyService;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agency\OfficeService;
use App\Services\Agency\SearchOfficeService;
use App\Services\Agency\UpdateOfficeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


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
    public function index(Request $request, int $agencyId): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new SearchOfficeService($request->toArray());
            return OfficeResource::collection($service->get($agencyId));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
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
        try{

            $ofcAndAgencySvc = new CreateOfficeAndAgency($request->toArray());
            $agentAndUserSvc = new CreateAgentAndUser();
            $inputData = $request->toArray();

            $officeData = $inputData['office'];
            $agentData = $inputData['agent'];
            $officeCommissions = $inputData['office_commissions'];

            //create new office
            $office = $ofcAndAgencySvc->createOffice($officeData);
            $agentData['office_id'] = $office->id;
            $agentData['agency_id'] = $officeData['agency_id'];

            //create agent and user
            $agent = $agentAndUserSvc->createAgent($agentData);
            $agentData['profile_id'] = $agent->id;
            $user = $agentAndUserSvc->createUser($agentData);

            //create commission with data
            $commissions = $ofcAndAgencySvc->createCommistions($officeCommissions, $office->id, $officeData['agency_id']);
            return AgencyResource::make($office);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
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
    public function createAgencyOffice(CreateOfficeRequest $request, int $agencyId): AgencyResource | JsonResponse
    {
        try {
            $agencySvc = new AgencyService();
            $agency = $agencySvc->getAgency($agencyId);

            $inputData = $request->toArray();
            $officeData = $inputData['office'];
            $officeData['agency_id'] = $agency->id;
            $officeCommissionsData = $inputData['office_commissions'];
            $officeAllocatorData = $inputData['agent'];
            $officeAllocatorData['agency_id'] = $agency->id;

            // Create service instances.
            $ofcAndAgencySvc = new CreateOfficeAndAgency();
            $agentAndUserSvc = new CreateAgentAndUser();

            // Create office.
            $office = $ofcAndAgencySvc->createOffice($officeData);

            // Create office allocator (agent) profile.
            $officeAllocatorData['office_id'] = $office->id;
            $agent = $agentAndUserSvc->createAgent($officeAllocatorData);

            // Create office commissions.
            $commissions = $ofcAndAgencySvc->createCommistions(
                $officeCommissionsData, $office->id, $officeData['agency_id']);

            return AgencyResource::make($office);
        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function updateOffice(UpdateOfficeRequest $request, int $id)
    {
        try {
            $service = new UpdateOfficeService($id);
            return response()->json(['success' => false, 'message' => $service->updateOffice($request->toArray())]);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getOffice(int $id)
    {

        try {
            $service = new OfficeService($id);
            return response()->json(['success' => true, 'data' => $service->getOffice()]);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getOnlyOffice(int $id)
    {
        try {
            $service = new OfficeService($id);
            return response()->json(['success' => true, 'data' => $service->getOnlyOffice()]);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
