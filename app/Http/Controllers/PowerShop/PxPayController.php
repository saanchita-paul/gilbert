<?php

namespace App\Http\Controllers\PowerShop;
use App\Http\Controllers\Controller;
use App\Services\PowerShop\CAFGenerationService;
use App\Services\PowerShop\PxPayService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 *
 */
class PxPayController extends Controller
{
    /**
     * @param PxPayService $pxPayService
     */
    public function __construct(private PxPayService $pxPayService)
    {
        $this->pxPayService = new PxPayService();
    }

    /**
     * @throws GuzzleException
     */
    public function invite(Request $request): JsonResponse|Redirector
    {
        try {
            return redirect($this->pxPayService->getRedirectUrl());
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function handleSuccess(Request $request)
    {
        return "Yooo! You have successfully validate your card information!";
    }

    /**
     * @param Request $request
     * @return string
     */
    public function handleFailure(Request $request): string
    {
        return "Sorry! You have failed to validate your card information! Please Contact to 01457889!";
    }

    /**
     * @throws GuzzleException
     */
    public function handleCallback(Request $request): JsonResponse
    {
        try {
            $cardDetails = $this->pxPayService->handleCallback($request->toArray());

            return response()->json(['success' => true, 'data' => $cardDetails]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
