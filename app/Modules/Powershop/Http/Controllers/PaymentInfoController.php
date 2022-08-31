<?php

namespace Powershop\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PowershopPaymentInfo;
use App\Services\PowerShop\PxPayService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Powershop\Services\PaymentInfoService;

class PaymentInfoController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse|Redirector
     */
    public function inviteCustomer(Request $request): JsonResponse|Redirector
    {
        $service = new PaymentInfoService($request->get('app_id'));
        try {
            $info = $service->inviteCustomer($request->get('link_type'));
            return response()->json(['data' => $info]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updatePaymentInfo(Request $request): JsonResponse
    {
        $service = new PaymentInfoService($request->get('app_id'));
        try {
            return response()->json(['data' => $service->update($request->toArray())]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
