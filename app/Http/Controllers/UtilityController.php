<?php

namespace App\Http\Controllers;

use App\Services\Utility\FastConnectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    /**
     * Getting Address from third party API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fcAuth(Request $request): JsonResponse
    {
        try {
            $fcService = new FastConnectService();
            $authentication = $fcService->authenticate();

            if ($authentication == null)
            {
                return response()->json(['success' => false, 'data' => $authentication]);
            }

            return response()->json(['success' => true, 'data' => $authentication]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function fcAddress(Request $request): JsonResponse
    {
        try {
            $body = $request->getContent();
            $fcService = new FastConnectService();
            $authentication = $fcService->findAddress($body, $request->bearerToken());

            if ($authentication == null)
            {
                return response()->json(['success' => false, 'data' => $authentication]);
            }

            return response()->json(['success' => true, 'data' => $authentication]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
