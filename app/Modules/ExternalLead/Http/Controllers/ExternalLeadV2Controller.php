<?php

namespace ExternalLead\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExternalSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ExternalLead\Http\Requests\ValidateCreateLeadRequest;
use ExternalLead\Http\Requests\ValidateCreateSourceRequest;
use ExternalLead\Services\AuthService;
use ExternalLead\Services\CreateLeadService;
use ExternalLead\Services\CreateExternalSourceService;
use App\Models\Office;

class ExternalLeadV2Controller extends Controller
{
    /**
     * this is the controller which will return access token.
     *
     * @param  Request $requst
     * @return JsonResponse
     * @throws Exception
     */
    public function getAccessToken(Request $request): JsonResponse {
        try {
            $authService = new AuthService();
            $result = $authService->generateAccessToken($request->toArray());
            return response()->json($result, 200);
        } catch (\Exception $exception) {
            \Log::error("Error in ExternalLeadV2Controller, getAccessToken method", ['message' => $exception->getMessage()]);
            \Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'failed',
                'message' => "email and password does not match"
            ];
            return response()->json($response, 200);
        }
    }

    public function createLead(ValidateCreateLeadRequest $request)
    {
        try {
            Log::info('** Create External Leads Request Body', [$request->toArray()]);
            $externalSource = ExternalSource::where('email', $request->username ?? '')->firstOrFail();
            $service = new CreateLeadService();
            $newLead = $service->create($externalSource, $request->all());
            $response = [
                "status" => "success",
                "hood_lead_id" => $newLead->id,
                "message" => "Hood lead has been added successfully"
            ];
            return response($response, 201);
        } catch (\Exception $ex) {
            \Log::error("Hood lead can not be stored");
            \Log::error($ex->getMessage());
            \Log::error($ex->getTraceAsString());
            $response = [
                "status" => "failed",
                "message" => $ex->getMessage()
            ];
            return response($response, 400);
        }
    }

    public function createSource(ValidateCreateSourceRequest $request)
    {
        $service = new CreateExternalSourceService();
        $newSource = $service->save($request->all());

        return response(['external_source_id' => $newSource->id], 200);
    }
}
