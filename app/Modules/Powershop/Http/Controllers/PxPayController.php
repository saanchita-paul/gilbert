<?php

namespace Powershop\Http\Controllers;
use App\Http\Controllers\Controller;
use Powershop\Services\PxPayService;
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
     * @param $id
     * @return Application|JsonResponse|RedirectResponse|Redirector
     * @throws GuzzleException
     */

    public function acceptInvite($id): JsonResponse|Redirector|Application|RedirectResponse
    {
        try {
            $service = new PxPayService();
            return  redirect($service->getRedirectUrl($id));
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
        try {
            $name = $this->pxPayService->handleSuccess($request->toArray());

            return view('powershop.success', ['name' => $name]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function handleFailure(Request $request)
    {
        try {
            $info= $this->pxPayService->handleFailed($request->toArray());

            return view('powershop.failed', [
                'name' => $info->customer_full_name,
                'reason' => $info->px_response_text_desc
            ]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function handleCallback(Request $request): JsonResponse
    {
        try {
            $this->pxPayService->handleCallback($request->toArray());

            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
