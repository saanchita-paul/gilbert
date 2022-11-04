<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationServiceBulkStatusChangeRequest;
use App\Http\Requests\ApplicationServiceStatusChangeRequest;
use App\Http\Requests\ApplicationServiceStatusRequest;
use App\Http\Resources\ApplicationServiceStatusResource;
use App\Models\ApplicationServiceStatus;
use App\Services\Application\ApplicationServiceStatusService;
use App\Services\Application\ServiceStatusFilterMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ApplicationServiceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ApplicationServiceStatusResource::collection(ApplicationServiceStatus::whereIsActive(1)->get())->response();
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

    public function changeStatus(ApplicationServiceStatusChangeRequest $request)
    {
        try {
            $service = new ApplicationServiceStatusService($request->except('_token', '_method'));
            $service->saveStatus();
            return $this->sendSuccessResponse('Application service status changed successfully.');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function changeBulkStatus(ApplicationServiceBulkStatusChangeRequest $request)
    {
        try {
            $file = $request->file('file')->store('status-files');
            $collection = (new FastExcel)->import(utf8_encode(storage_path('app/' . $file)));
            $service = new ApplicationServiceStatusService();
            $service->saveBulkStatus($collection->toArray());
            if (file_exists(storage_path('app/' . $file))) {
                unlink(storage_path('app/' . $file));
            }
            return $this->sendSuccessResponse('Application service status changed successfully.');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }


    public function getServiceStatusDD(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:connection_services,id',
            'application_status' => 'required',
            'service_new_status' => 'required'
        ]);
        $service = new ServiceStatusFilterMapper();
        $statuses = $service->getStatuses($request->application_status, $request->service_id, $request->service_new_status);
        $serviceStatuses = ApplicationServiceStatus::where('type', 'service')->whereIn('status_value', $statuses)->get();
        return ApplicationServiceStatusResource::collection($serviceStatuses)->response();
    }
}
