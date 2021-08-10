<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgencyResource;
use App\Models\User;
use App\Services\Agency\AgencyService;
use App\Services\Agency\SearchAgencyService;
use App\Services\Office\OfficeService;
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

    public function create(Request $request)
    {
        try{
            $service = new AgencyService();
            return AgencyResource::make($service->createAgency($request->toArray()));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function createOffice(Request $request)
    {
        try{
            $service = new OfficeService();
            $inputData = $request->toArray();
            $officeData = $inputData['office'];
            $agentData = $inputData['agent'];
            $officeCommissions = $inputData['office_commissions'];

            $office = $service->createOffice($officeData);
            $agentData['office_id'] = $office->id;
            $agentData['agency_id'] = $officeData['agency_id'];
            $agent = $service->createAgent($agentData);
            $commissions = $service->createCommistions($officeCommissions, $office->id, $officeData['agency_id']);
            return AgencyResource::make($office);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


}
