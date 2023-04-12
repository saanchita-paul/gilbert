<?php

namespace App\Http\Controllers;

use App\Http\Resources\GoodtelPlanResource;
use App\Services\GoodtelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoodtelController extends Controller
{
    public function getPlans()
    {
        try {
            $plans = (new GoodtelService())->getPlans();

            return GoodtelPlanResource::collection($plans);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function create(Request $request)
    {
        try {
            $service = new GoodtelService();
            $service->updateOrCreatePlans($request->get('plans'));

            return $this->sendSuccessResponse('Plans created successfully!');
        } catch (\Exception|\Throwable $e) {
            return $this->sendErrorResponse($e);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function sendPaymentLink(int $id)
    {
        try {
            $service = new GoodtelService();
            $service->paymentLinkSend($id);

            return $this->sendSuccessResponse('Payment link sent successfully!');
        } catch (\Exception|\Throwable $e) {
            return $this->sendErrorResponse($e);
        }
    }

    /**
     * Get all modems
     *
     * @return JsonResponse
     */
    public function getModems(): JsonResponse
    {
        try {
            return response()->json(['data' => GoodtelService::getModems()]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}
