<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agent\ApplicationResource;
use App\Models\ConnectionApplication;
use App\Services\Agent\ApplicationService;
use App\Services\Agent\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{

    /**
     * Getting Application list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */

    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->get());

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


    /**
     * Create new application
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function create(Request $request)
    {
        try{
            $service = new ApplicationService();
            return ApplicationResource::make($service->createApplication($request->toArray()));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Getting Agency list
     *
     * @param Request $request
     *
     * @return ApplicationResource|JsonResponse
     */
    public function summary(Request $request, ConnectionApplication $application): ApplicationResource | JsonResponse
    {
        try {
            $application->load(['connectionServices']);
            return new ApplicationResource($application);

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
