<?php

namespace OurProperty\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OurProperty\Services\CreateOurPropertyService;


class OurPropertyController extends Controller
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

            $ourPropertyService = new CreateOurPropertyService();
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

    public function createOurProperty(Request $request)
    {
        try {

            $service = new CreateOurPropertyService();
            $ourProperty = $service->create($request);
            $response = [
                "status" => "success",
                "hood_lead_id" => $ourProperty->id,
                "message" => "Hood lead has been added successfully"
            ];
            return response($response, 200);
        } catch (\Exception $ex) {
            return $ex;
            $response = [
                "status" => "failed",
                "message" => "Hood lead can not be stored"
            ];
            return response($response, 400);
        }
    }
}
