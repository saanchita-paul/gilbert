<?php

namespace App\Services\GilbertLead;
use App\Models\ConnectionService;
use Illuminate\Http\Request;

class UpdateGilbertConnectionService
{
    /*
     * update connection service
     * */
    public function updateConnection(Request $data, $id)
    {
        $connectionService = $data->toArray();
        foreach ($connectionService as $service){
            if($service['service_type']){
                 ConnectionService::query()->where('connection_application_id', $id)
                    ->updateOrCreate(['service_type'   => $service['service_type']], [
                        'connection_application_id'   => $id,
                        'plan_type'   => $service['plan_type']
                    ]);
            }
        }
    }
}
