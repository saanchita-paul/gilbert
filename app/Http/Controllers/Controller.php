<?php

namespace App\Http\Controllers;

use Exception;
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

        return response()->json(['success' => false, 'message' => $err->getMessage()]);
    }
}
