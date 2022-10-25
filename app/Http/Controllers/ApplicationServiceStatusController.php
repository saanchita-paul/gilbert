<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationServiceStatusRequest;
use App\Http\Resources\ApplicationServiceStatusResource;
use App\Models\ApplicationServiceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationServiceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ApplicationServiceStatusResource::collection(ApplicationServiceStatus::all())->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationServiceStatusRequest $request
     * @return JsonResponse
     */
    public function store(ApplicationServiceStatusRequest $request)
    {
        try {
            ApplicationServiceStatus::create($request->validated());
            return $this->sendSuccessResponse('Application service status created successfully.');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ApplicationServiceStatus $applicationServiceStatus
     * @return JsonResponse
     */
    public function show(ApplicationServiceStatus $applicationServiceStatus)
    {
        try {
            return (new ApplicationServiceStatusResource($applicationServiceStatus))->response();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApplicationServiceStatusRequest $request
     * @param ApplicationServiceStatus $applicationServiceStatus
     * @return JsonResponse
     */
    public function update(ApplicationServiceStatusRequest $request, ApplicationServiceStatus $applicationServiceStatus)
    {
        try {
            $applicationServiceStatus->update($request->validated());
            return $this->sendSuccessResponse('Application service status updated successfully.');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $applicationServiceStatus = ApplicationServiceStatus::findOrFail($id);
            $applicationServiceStatus->delete();
            return $this->sendSuccessResponse('Application service status deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}
