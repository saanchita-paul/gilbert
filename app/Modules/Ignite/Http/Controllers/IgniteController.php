<?php

namespace Ignite\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Http\Requests\IgniteRequest;
use Ignite\Services\SugerLeadService;
use Illuminate\Http\Request;

class IgniteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $sugerLead = new SugerLeadService();
            $connectionApplication = $sugerLead->create($request);
            $response = [
                "status" => "success" ,
                "hood_lead_id" => $connectionApplication->id ,
                "message" =>  "Hood lead has been added successfully"
            ];
            return response( $response , 200 );
        } catch (\Exception $ex) {
            //throw $th;
            \Log::error("Problem in Storing data");
            \Log::error($ex->getMessage());
            $response = [
                "status" => "failed" ,
                "message" =>  "Hood lead can not be stored"
            ];
            return response( $response , 400 );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(IgniteRequest $request)
    {   
        try {
            return 'trying ignite controller';
            // $sugerLead = new SugerLeadService();
            // $connectionApplication = $sugerLead->show($request->from , $request->to , $request->lead_id );
            // $response = [
            //     "status" => "success" ,
            //     "data" => $connectionApplication
            // ];
            // return response( $response , 200 );
        } catch (\Exception $th) {
            //throw $th;
            $response = [
                "status" => "failed" ,
                "data" => "Your lead id $request->lead_id is not found"
            ];
            return response( $response , 404 );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sugerLead = new SugerLeadService();
            $sugerLead->update($request , $id);
            $response = [
                "status"  => "success",
                "message" =>  "Your hood lead has been updated"
            ];
            return response( $response , 200 );
        } catch (\Throwable $th) {
            $response = [
                "status"  => "failed",
                "message" =>  "Hood Lead Id: $id is not found"
            ];
            return response( $response , 404 );
        }
    }
}
