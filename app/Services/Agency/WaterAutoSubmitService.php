<?php

namespace App\Services\Agency;

use Exception;
use App\Jobs\WaterAutoSubmitJob;
use App\Models\ConnectionApplication;

class WaterAutoSubmitService
{
    const STATUS_AUTO_SUBMIT_TRUE = 1;


    public function __construct(int $lead_id)
    {
        info("inside water auto submit service");
        \Log::info($lead_id);
        try {
            $connectionApplcation = ConnectionApplication::with(['connectionServices' , 'identification'])->where( 'id' ,  $lead_id)->first();
            $this->validateData($connectionApplcation);
            $this->updateConnectionApplication($connectionApplcation);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    }
    
    private function updateConnectionApplication($connectionApplcation){
        
        try {
            $connectionApplcation->update(['is_auto_water_submit'=> WaterAutoSubmitService::STATUS_AUTO_SUBMIT_TRUE ]);
            WaterAutoSubmitJob::dispatch($connectionApplcation->id);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    
    }


    private function validateData($connectionApplcation){
        info('checking connection application');
        \Log::info($connectionApplcation);
        // \Log::info($connectionApplcation->identification['connection_application_id']);
        try {
            if(
                    isset($connectionApplcation->street_number)  &&  
                    isset($connectionApplcation->street_name)  &&  
                    isset($connectionApplcation->city) &&  
                    isset($connectionApplcation->postcode)  &&
                    isset($connectionApplcation->first_name) &&
                    isset($connectionApplcation->last_name) &&
                    isset($connectionApplcation->dob) &&
                    isset($connectionApplcation->email) &&
                    isset($connectionApplcation->phone)  && 
                    isset($connectionApplcation->state)  && 
                    isset($connectionApplcation->title)  && 
                    isset($connectionApplcation->tenancy_type)  && 
                    isset($connectionApplcation->identification) 
              ){
                    info('lead passed');
                    // throw new Exception('invalid field found in connection application table');
                } else{
                    throw new Exception('invalid field found in connection application table');
                }


                if(
                    isset($connectionApplcation->identification['card_number']) &&
                    isset($connectionApplcation->identification['expire_date']) 
                ){
                    return true;
                }else{
                    throw new Exception('invalid data in identifcation table');
                }

            

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw new Exception("Error Processing Request", 1);
        }
    }
}
