<?php

namespace App\Services\GilbertLead;
use App\Models\ConnectionApplication;

class GetGilbertLeadService
{
    /*
     * get api from connection application
     * */
    public function gilbertLeads()
    {
        $connection_application = ConnectionApplication::latest()->get();
        return $connection_application;
    }
}
