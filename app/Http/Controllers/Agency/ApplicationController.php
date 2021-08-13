<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\ApplicationRequest;
use App\Http\Resources\Agency\AgencyResource;
use App\Http\Resources\Agency\ApplicationNoteResourse;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Agent\AgentProfileService;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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
        /** @var User $user */
        $user = auth()->user();
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->get($user));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }


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

    /**
     * @param Request $request
     * @param ConnectionApplication $application
     * @return JsonResponse
     */
    public function getConnectionNotes(string $application)
    {
        try {
            $service = new ApplicationService();
            return ApplicationNoteResourse::collection($service->getNotes($application));
        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function createConnectionNotes(Request $request, string $id):ApplicationNoteResourse|JsonResponse
    {
        try {
            $service = new ApplicationService();
            $user = Auth::user();
            return ApplicationNoteResourse::make($service->createNotes($request->toArray(), $user, $id));

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
