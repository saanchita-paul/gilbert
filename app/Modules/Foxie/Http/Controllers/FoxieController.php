<?php

namespace Foxie\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CreateHubspotProperty;
use Foxie\Http\Requests\FoxieRequest;
use Foxie\Services\SugerLeadService;
use Illuminate\Http\Request;

class FoxieController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $sugerLead = new SugerLeadService();
            $connectionApplication = $sugerLead->create($request);
            $response = [
                "status" => "success",
                "hood_lead_id" => $connectionApplication->id,
                "message" => "Hood lead has been added successfully"
            ];

            // hubspot api call for creation
            CreateHubspotProperty::dispatch($connectionApplication->id);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(FoxieRequest $request)
    {
        try {
            $service = new SugerLeadService();
            $connectionApplication = empty($request->lead_id)
                ? $service->get($request->from, $request->to)
                : $service->findById($request->lead_id);

            $response = [
                "status" => "success",
                "data" => $connectionApplication
            ];
            return response($response, 200);
        } catch (\Exception $th) {
            //throw $th;
            $response = [
                "status" => "failed",
                "data" => "Your lead id $request->lead_id is not found"
            ];
            return response($response, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sugerLead = new SugerLeadService();
            $sugerLead->update($request, $id);
            $response = [
                "status" => "success",
                "message" => "Your hood lead has been updated"
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                "status" => "failed",
                "message" => "Hood Lead Id: $id is not found"
            ];
            return response($response, 404);
        }
    }
}
