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
     */
    public function show(Request $request)
    {   
        try {
            \Log::info($request->all());
            $service = new IgniteConnectionLeadService();
            $token =  $service->authenticate();
            $leads =  $service->getIgniteLeads($token);
            \Log::info('in the show method');
            \Log::info($token);
            \Log::info($leads);
            \Log::info($leads[0]['application']['id']);
            $createLeadService = new IgniteLeadService();
            $createLeadService->create($leads);
            return 'trying ignite controller';
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            $response = [
                "status" => "failed" ,
                "data" => "Ignite lead can not be retriev"
            ];
            return response( $response , 404 );
        }
    }
}
