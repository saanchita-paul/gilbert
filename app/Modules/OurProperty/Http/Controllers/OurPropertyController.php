<?php

namespace OurProperty\Http\Controllers;

use App\Http\Controllers\Controller;
use OurProperty\Services\CreateOurPropertyService;
use Illuminate\Http\Request;

class OurPropertyController extends Controller
{
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
