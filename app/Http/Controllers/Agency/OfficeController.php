<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateOfficeRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\OfficeResource;
use App\Services\Agency\CreateAgentAndUser;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agency\SearchOfficeService;
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

            $ofcAndAgencySvc = new CreateOfficeAndAgency();
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
}
