<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\CreateIndependentAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\IndependentAgencyResource;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\User;
use App\Services\Agency\AgencyMetricByApplication;
use App\Services\Agency\AgencyMetricService;
use App\Services\Agency\AgencyService;
use App\Services\Agency\IndepentAgencyService;
use App\Services\Agency\SearchAgencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Services\Agency\Report\ExportReaAgenciesReport;

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
            return $this->sendErrorResponse($exception);
        }
    }

    public function create(CreateAgencyRequest $request)
    {
        try {
            $service = new AgencyService();
            return AgencyResource::make($service->createAgency($request->toArray()));

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
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
            return $this->sendErrorResponse($exception);
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
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAgency(int $id)
    {
        try {

            $authUser = Auth::user();
            if($authUser->profile_type === AgentProfile::class &&
                $authUser->profile->agency_id !== $id) {
                return $this->sendUnauthorizedResponse();
            }

            $service = new AgencyService();
            return AgencyResource::make($service->getAgency($id));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAgencyMetrics(Request $request)
    {
        try {
            $service = new AgencyMetricService($request->toArray());
            return response()->json(['success' => true, 'data' => $service->getAgencyMetrics()]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function getAgencyApplicationMetrics(Request $request)
    {
        /** @var User  $user */
        $user = auth()->user();
        if ($user->profile_type === AgentProfile::class && $user->profile->office_id !== (int) $request->get('office_id')) {
            return $this->sendUnauthorizedResponse();
        }
        try {
            $service = new AgencyMetricByApplication($request->toArray());
            return response()->json(['success' => true, 'data' => $service->getAgencyMetrics()]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function download(){
        try {
            return (new ExportReaAgenciesReport())->run();
        } catch (\Exception $exception) {
            return response(['msg' => $exception->getMessage()], 500);
        }
    }

}
