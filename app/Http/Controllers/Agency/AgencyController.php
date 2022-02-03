<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\CreateIndependentAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\IndependentAgencyResource;
use App\Services\Agency\AgencyMetricByApplication;
use App\Services\Agency\AgencyMetricService;
use App\Services\Agency\AgencyService;
use App\Services\Agency\IndepentAgencyService;
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
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $service = new SearchAgencyService($request->toArray());
            return AgencyResource::collection($service->get());

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function create(CreateAgencyRequest $request)
    {
        try {
            $service = new AgencyService();
            return AgencyResource::make($service->createAgency($request->toArray()));

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param UpdateAgencyRequest $request
     * @param int $id
     * @return AgencyResource|JsonResponse
     */
    public function update(UpdateAgencyRequest $request, int $id): AgencyResource|JsonResponse
    {
        try {
            $service = new AgencyService();
            return AgencyResource::make($service->updateAgency($request->toArray(), $id));
        } catch (\Exception $exception) {
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
    public function createIndependentAgency(CreateIndependentAgencyRequest $request)
    {
        try {
            $independentAgencyService = new IndepentAgencyService();
            return IndependentAgencyResource::make($independentAgencyService->create($request->toArray()));
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getAgency(int $id)
    {
        try {
            $service = new AgencyService();
            return AgencyResource::make($service->getAgency($id));
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getAgencyMetrics(Request $request)
    {
        try {
            $service = new AgencyMetricService($request->toArray());
            return response()->json(['success' => true, 'data' => $service->getAgencyMetrics()]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getAgencyApplicationMetrics(Request $request)
    {
        try {
            $service = new AgencyMetricByApplication($request->toArray());
            return response()->json(['success' => true, 'data' => $service->getAgencyMetrics()]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
