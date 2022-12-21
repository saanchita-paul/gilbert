<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\ApplicationResource;
use App\Services\Agency\NbnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NbnController extends Controller
{
    /**
     * Assigning user to an Application
     *
     * @param Request $request
     * @param int $applicationId
     * @return ApplicationResource|JsonResponse
     */
    public function updateInternetServiceInfo(Request $request, int $applicationId)
    {
        try {
            $service = new NbnService();

            return ApplicationResource::make($service->updateInternetServiceInfo($request->toArray(), $applicationId));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param $applicationId
     * @return JsonResponse
     */
    public function updateNbnProvider(Request $request, $applicationId)
    {
        try {
            $service = new NbnService();
            $inputData = $request->toArray();
            $service->updateNbnProvider($inputData, $applicationId);

            return response()->json(['success' => true, 'message' => 'Providers updated successfully.']);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param $applicationId
     * @return JsonResponse
     */
    public function submitNbn(Request $request, $applicationId)
    {
        try {
            $service = new NbnService();
            $inputData = $request->toArray();;

            return ApplicationResource::make($service->submitNBN($inputData, $applicationId));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
