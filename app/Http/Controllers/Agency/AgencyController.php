<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Services\Agency\AgencyService;
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

}
