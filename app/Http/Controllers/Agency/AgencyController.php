<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\CreateOfficeRequest;
use App\Http\Requests\Agency\CreateIndependentAgencyRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\IndependentAgencyResource;
use App\Services\Agency\AgencyService;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agency\SearchAgencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgencyController extends Controller
{
    /**
     * Getting Agency list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new SearchAgencyService($request->toArray());
            return AgencyResource::collection($service->get());

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function create(CreateAgencyRequest $request)
    {
        try{
                $service = new AgencyService();
                return AgencyResource::make($service->createAgency($request->toArray()));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Creating an Independent Agency.
     *
     * @param CreateIndependentAgencyRequest $request
     *
     * @return IndependentAgencyResource|JsonResponse
     */
    public function createIndependentAgency(CreateIndependentAgencyRequest $request): IndependentAgencyResource | JsonResponse
    {
        try {
            $inputData = $request->toArray();
            $officeData = $inputData['office'];
            $agencyData = $inputData['agency'];

            $agencySvc = new AgencyService();
            $agency = $agencySvc->createAgency($agencyData);

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

            return IndependentAgencyResource::make($agency);
        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
