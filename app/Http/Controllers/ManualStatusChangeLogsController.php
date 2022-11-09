<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualStatusChangeLogResource;
use App\Services\ManualStatus\ManualStatusChangeLogService;
use Illuminate\Http\JsonResponse;

class ManualStatusChangeLogsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param $applicationId
     * @return JsonResponse
     */
    public function statusLogsByApplicationId($applicationId)
    {
        $logs = ManualStatusChangeLogService::getLogsByApplicationId($applicationId);
        return ManualStatusChangeLogResource::collection($logs)->response();
    }
}
