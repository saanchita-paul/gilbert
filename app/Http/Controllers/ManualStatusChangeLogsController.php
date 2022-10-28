<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualStatusChangeLogResource;
use App\Models\ManualStatusChangeLog;
use Illuminate\Http\JsonResponse;

class ManualStatusChangeLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ManualStatusChangeLogResource::collection(ManualStatusChangeLog::all())->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function statusLogsByApplicationId($id)
    {
        $logs = ManualStatusChangeLog::where('connection_application_id', $id)->get();
        return ManualStatusChangeLogResource::collection($logs)->response();
    }
}
