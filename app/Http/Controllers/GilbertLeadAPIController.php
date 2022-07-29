<?php

namespace App\Http\Controllers;

use App\Http\Resources\GilbertLeadResource;
use App\Services\GilbertLead\GetGilbertLeadService;
use App\Services\GilbertLead\UpdateGilbertConnectionService;
use App\Services\GilbertLead\UpdateGilbertEnergyService;
use App\Services\GilbertLead\UpdateGilbertLeadService;
use Illuminate\Http\Request;

class GilbertLeadAPIController extends Controller
{
    /*
     * Get api from moving utility
     * */
    public function getGilbertLeads(Request $request){
        try {
            $lead = new GetGilbertLeadService();
            return GilbertLeadResource::collection($lead->gilbertLeads());
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
    /*
     * update gilbert leads
     * */
    public function updateGilbertLeads(Request $data, $id)
    {
        try {
            $leadUpdate = new UpdateGilbertLeadService();
            $leadUpdate->updateGilbert($data, $id);
            $message ='success';
            return $this->sendSuccessResponse($message);
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
    /*
     * final step api
     * */
    public function updateGilbertSteps($step)
    {
        try {
            return $this->sendSuccessResponse( $step);
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
    /*
     * update service api
     * */
    public function updateService(Request $data, $id)
    {
        try {
            $serviceUpdate = new UpdateGilbertConnectionService();
            $serviceUpdate->updateConnection($data, $id);
            $message ='success';
            return $this->sendSuccessResponse($message);
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
    /*
     * update energy plan
     * */
    public function updateGilbertEnergy($id, Request $data)
    {
        try {
            $energy = new UpdateGilbertEnergyService();
            $energy->updateEnergy($data, $id);
            $message ='success';
            return $this->sendSuccessResponse($message);
        }
        catch (Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

}
