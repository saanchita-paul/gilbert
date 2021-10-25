<?php

namespace App\Http\Controllers\Foxie;

use App\Http\Controllers\Controller;
use App\Services\Foxie\SugerLeadService;
use Illuminate\Http\Request;

class FoxieController extends Controller
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
        } catch (\Throwable $th) {
            //throw $th;
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
