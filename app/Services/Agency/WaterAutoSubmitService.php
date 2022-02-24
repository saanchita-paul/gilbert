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
        try {
            $lead = ConnectionApplication::with(['connectionServices' , 'identification'])->where( 'id' ,  $lead_id)->first();
            info('water auto submit service, lead info');
            if (!$lead->is_auto_water_submit) {
                info('water auto submit service, lead info, inside, turned on');
                $lead->update(['is_auto_water_submit'=> WaterAutoSubmitService::STATUS_AUTO_SUBMIT_TRUE ]);
                $this->validateData($lead);
                $this->updateConnectionApplication($lead);
            }

        } catch (\Exception $exception) {
            info('water auto submit service, lead info, inside, turned off in service');
            $lead->update(['is_auto_water_submit'=> 0 ]);
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }
    }

    private function updateConnectionApplication(ConnectionApplication $connectionApplcation){

        try {
            // $connectionApplcation->update(['is_auto_water_submit'=> WaterAutoSubmitService::STATUS_AUTO_SUBMIT_TRUE ]);
            
            $waterService = new ApplicationService();
            $waterService->setSubmittedAtByServiceType($connectionApplcation->id, 'water');
            
            WaterAutoSubmitJob::dispatch($connectionApplcation->id);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

    }


    private function validateData(ConnectionApplication $connectionApplcation){
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

    public function checkTenancyType(ConnectionApplication $connectionApplcation) : bool {
        return $connectionApplcation->tenancy_type == ConnectionApplication::TENANCY_TYPE_HOME_OWNER ? true : 
        throw new Exception('invalid data in identifcation table, tenancy type');
    }

    public function checkTenancyTypeDob(ConnectionApplication $connectionApplcation) : bool {

        if($connectionApplcation->tenancy_type == ConnectionApplication::TENANCY_TYPE_HOME_OWNER){
            return true;
        }else if(isset($connectionApplcation->dob)){
            return true;
        }else{
            return false;
        }
    }
}
