<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\OfficeResource;
use App\Services\Agency\SearchOfficeService;
use App\Services\Office\OfficeService;
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
