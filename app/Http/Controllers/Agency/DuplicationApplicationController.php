<?php


namespace App\Http\Controllers\Agency;


use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\DuplicationApplicationResource;
use App\Services\DuplicateApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DuplicationApplicationController extends Controller
{
    /**
     * @param $applicationId
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getDuplicateLeads($applicationId): JsonResponse|AnonymousResourceCollection
    {
        try {
            $service = new DuplicateApplicationService($applicationId);
            return DuplicationApplicationResource::collection($service->get());

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
