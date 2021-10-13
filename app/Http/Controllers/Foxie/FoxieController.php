<?php

namespace App\Http\Controllers\Foxie;

use App\Http\Controllers\Controller;
use App\Models\Foxie\SugerLeads;
use Illuminate\Http\Request;

class FoxieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // return "foxite lead testing";
        // return json_encode($request->all());
        $sugerLeads =  SugerLeads::create(["all_fields_dump" => json_encode($request->all())]);
        return response([ "status" => "success" , "lead_id" => $sugerLeads->id , "message" =>  "lead has been added successfully" ] , 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        $lead = SugerLeads::find($id);

        if ($lead) {
            $all_fields_dump =  json_decode($lead->all_fields_dump);
            $mergedUpdatedData =  collect($all_fields_dump)->merge($request->all());
            $lead->update(["all_fields_dump" => json_encode($mergedUpdatedData)]);
            return response(["status" => "success", "message" =>  "Your lead has been updated"], 202);
        } else {
            return response(["status" => "failed", "message" =>  "Lead Id: $id is not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
