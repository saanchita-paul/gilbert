<?php

namespace ExternalLead\Http\Controllers;

use App\Http\Controllers\Controller;
use ExternalLead\Services\TAppServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OurProperty\Services\CreateOurPropertyService;


class ExternalLeadController extends Controller
{

    /**
     * this is the controller which will return access token.
     *
     * @param  Request $requst
     * @return JsonResponse
     * @throws Exception
     */
    public function getAccessToken(Request $request) : JsonResponse {
        try {

            $ourPropertyService = new TAppServices();
            $result =  $ourPropertyService->generateAccessToken($request->toArray());
            return  response()->json($result , 200);

        } catch (\Exception $exception) {

            \Log::error( "Error in OurPropertyController, getAccessToken method" , [ 'message' => $exception->getMessage()]);
            \Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'failed',
                'message' => "email and password does not match"
            ];
            return response()->json($response , 200);

        }
    }

    public function createLeads(Request $request)
    {
        try {

            Log::info('** TAPP Request Body',[$request->toArray()]);
            $service = new TAppServices();
            $tApp = $service->create($request);
            $response = [
                "status" => "success",
                "hood_lead_id" => $tApp->id,
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
}
