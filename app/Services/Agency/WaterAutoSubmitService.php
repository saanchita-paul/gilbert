<?php

namespace App\Services\Agency;

use App\Jobs\WaterAutoSubmitJob;
use App\Models\ConnectionApplication;
use Exception;
use FastConnect\Services\SubmitWaterLeadToFastConnect;

class WaterAutoSubmitService
{
    const STATUS_AUTO_SUBMIT_TRUE = 1;


    /**
     * @throws Exception
     */
    public function __construct(int $lead_id)
    {
        try {
            $lead = ConnectionApplication::with(['connectionServices', 'identification'])->where('id', $lead_id)->firstOrFail();
            if (!$lead->is_auto_water_submit) {
                $lead->update(['is_auto_water_submit' => WaterAutoSubmitService::STATUS_AUTO_SUBMIT_TRUE]);
                $this->validateData($lead);
                $this->updateConnectionApplication($lead);
            }

        } catch (\Exception $exception) {
            if (isset($lead)) {
                $lead->update(['is_auto_water_submit' => 0]);
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
                // Saving Failed reason and set Water status as Failed
                $service = new SubmitWaterLeadToFastConnect($lead->id);
                $service->saveRejectionReason($exception->getMessage(), $lead->id, 'water');
                $service->setStatusFailed($lead->id, 'water');
            }


            throw new Exception("EAuto Water Submit failed: ". $exception->getMessage());
        }
    }

    private function updateConnectionApplication(ConnectionApplication $connectionApplcation)
    {

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


    /**
     * @throws Exception
     */
    private function validateData(ConnectionApplication $connectionApplcation)
    {
        if (
            isset($connectionApplcation->street_number) &&
            isset($connectionApplcation->street_name) &&
            isset($connectionApplcation->city) &&
            isset($connectionApplcation->postcode) &&
            isset($connectionApplcation->first_name) &&
            isset($connectionApplcation->last_name) &&
            isset($connectionApplcation->dob) &&
            isset($connectionApplcation->email) &&
            isset($connectionApplcation->phone) &&
            isset($connectionApplcation->state) &&
            isset($connectionApplcation->title) &&
            isset($connectionApplcation->tenancy_type) &&
            isset($connectionApplcation->identification)
        ) {
            info('lead passed');
            // throw new Exception('invalid field found in connection application table');
        } else {
            throw new Exception('invalid field found in connection application table');
        }

        if (
            isset($connectionApplcation->identification['card_number']) &&
            isset($connectionApplcation->identification['expire_date'])
        ) {
            return true;
        } else {
            throw new Exception('invalid data in identifcation table');
        }


    }

    public function checkTenancyType(ConnectionApplication $connectionApplcation): bool
    {
        return $connectionApplcation->tenancy_type == ConnectionApplication::TENANCY_TYPE_HOME_OWNER ? true :
            throw new Exception('invalid data in identifcation table, tenancy type');
    }

    public function checkTenancyTypeDob(ConnectionApplication $connectionApplcation): bool
    {

        if ($connectionApplcation->tenancy_type == ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
            return true;
        } else if (isset($connectionApplcation->dob)) {
            return true;
        } else {
            return false;
        }
    }
}
