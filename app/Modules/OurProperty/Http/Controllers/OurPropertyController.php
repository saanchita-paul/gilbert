<?php

namespace OurProperty\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OurProperty\Services\CreateOurPropertyService;
use Illuminate\Http\Request;
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
            $service->create($request->toArray());
            $response = [
                "status" => "success",
                "hood_lead_id" => $connectionApplication->id,
                "message" => "Hood lead has been added successfully"
            ];
            return response($response, 200);
        } catch (\Exception $ex) {
            //throw $th;
            \Log::error("Problem in Storing data");
            \Log::error($ex->getMessage());
            \Log::error($ex->getTraceAsString());
            $response = [
                "status" => "failed",
                "message" => "Hood lead can not be stored"
            ];
            return response($response, 400);
        }
    }
}
