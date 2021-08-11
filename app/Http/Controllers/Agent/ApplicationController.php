<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ApplicationRequest;
use App\Http\Resources\Agent\ApplicationResource;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agent\AgentProfileService;
use App\Services\Agent\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Create new application
     *
     * @param ApplicationRequest $request
     *
     */
    public function create(ApplicationRequest $request)
    {
        try{
            /** @var  User $user */
            $user = Auth::user();

            $service = new ApplicationService();
            $inputData = $request->toArray();
            return ApplicationResource::make($service->createApplication($inputData, $user));

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
