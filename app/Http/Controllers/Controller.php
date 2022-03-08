<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Exception $err
     * @return JsonResponse
     */
    protected function sendErrorResponse(Exception $err): JsonResponse
    {
        \Log::error($err->getMessage());
        \Log::error($err->getTraceAsString());

        $message = env("APP_DEBUG") === true ? $err->getMessage() : "Server Error";

        return response()->json([
            'success' => false,
            'message' => $message,
//            'exception' => get_class($err)
        ]);
    }


    /**
     * @param string $mgs
     *
     * @return JsonResponse
     */
    protected function sendUnauthorizedResponse(string $mgs = "Unauthorized"): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $mgs], 403);
    }
}
