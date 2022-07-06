<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateAppClosingReasonRequest;
use App\Http\Requests\Agency\UpdateAppClosingReasonRequest;
use App\Http\Resources\Agency\AppCloseReasonResource;
use App\Services\Application\AppCloseReasonService;
use Illuminate\Http\Request;


class AppCloseReasonController extends Controller
{
    /**
     * Getting application close reasons list
     */
    public function index(){
        try {
            $service = new AppCloseReasonService();
            return AppCloseReasonResource::collection($service->getAppClosingReasonList());

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * create application close reason
     */

    public function create(CreateAppClosingReasonRequest $request)
    {
        try {
            $service = new AppCloseReasonService();
            return AppCloseReasonResource::make($service->createAppClosingReason($request->toArray()));

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * show application close reason
     */

    public function show(int $id)
    {
        try {
            $service = new AppCloseReasonService();
            return AppCloseReasonResource::make($service->showAppClosingReason($id));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * update application close reason
     */

    public function update(UpdateAppClosingReasonRequest $request, int $id)
    {
        try {
            $service = new AppCloseReasonService();
            return AppCloseReasonResource::make($service->updateAppClosingReason($request->toArray(), $id));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * delete application close reason
     */

    public function delete($id)
    {
        try {
            $service = new AppCloseReasonService();
            return $service->deleteAppClosingReason($id);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
