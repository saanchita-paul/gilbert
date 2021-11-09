<?php

namespace Ignite\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ignite\Services\IgniteConnectionLeadService;
use Ignite\Services\IgniteLeadService;

class IgniteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function insertLead()
    {   
        try {
            $createLeadService = new IgniteLeadService();
            $createLeadService->create();
            $response = [
                "status"  => "success" ,
                "message" => "All ignite leads have been created successfully"
            ];
            return response( $response , 201 );
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            $response = [
                "status" => "failed" ,
                "data"   => "Ignite lead can not be retriev"
            ];
            return response( $response , 404 );
        }
    }
}
