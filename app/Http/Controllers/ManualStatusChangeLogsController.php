<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualStatusChangeLogResource;
use App\Models\ManualStatusChangeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
