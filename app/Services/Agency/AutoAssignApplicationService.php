<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\OfficeAutoAssignTimeSlot;
use App\Models\Setting;
use App\Models\User;
use App\Services\SettingService;
use App\Services\TimeZoneService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class AutoAssignApplicationService
{
    /**
     * Auto assign application to chatbot
     * @param $application
     * @return void
     * @throws Exception
     */
    public function assignApplication($application)
    {
        try {
            if ($application->mirn && $application->nmi) {
                $this->assignUser($application);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function assignUser($application)
    {
        $chatbotUser = User::whereHas('roles', function ($query) {
            $query->where('name', 'hood_chatbot_user');
        })->first();

        if (!$chatbotUser) {
            Log::error('Chatbot user not found!');
            throw new Exception('AutoAssignApplicationService: Chatbot user not found!');
        }

        Log::info('Auto assign application to chatbot user: ', $chatbotUser->toArray());

        Log::info('Auto assign application: ', $application->toArray());

        $isAutoAssignable = $this->isAutoAssignable($application);
        Log::info('Auto assign application condition: ', [
            'auto_assign_condition' => $isAutoAssignable
        ]);

        if (!$isAutoAssignable) {
            throw new Exception('AutoAssignApplicationService: Application is not auto assignable!');
        }

        $applicationService = new ApplicationService();
        $applicationService->assignUser($chatbotUser->profile_id, $application->id);

        if ($application->tenancy_type != ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
            new WaterAutoSubmitService($application->id);
        }
    }

    /**
     * Get auto assign global setting
     * @return Setting|null
     */
    private function getAutoAssignGlobalSetting()
    {
        return SettingService::getOrCreate(Setting::AUTO_ASSIGN_TO_CHATBOT, 0);
    }

    /**
     * Check if auto assign is enabled
     * @return bool
     */
    public function isAutoAssignable($application)
    {
        $timeSlot = OfficeAutoAssignTimeSlot::where('office_id', $application->office_id)->first();
        if (!$timeSlot) {
            return false;
        }
        $currentTime = Carbon::now()->timezone(TimeZoneService::getTimeZoneArea());

        $allowableTime = $this->isAllowableTime($timeSlot->start_time, $timeSlot->end_time, $currentTime);

        Log::info('Auto assign application allowable time: ', [
            'start_time' => $timeSlot->start_time,
            'end_time' => $timeSlot->end_time,
            'current_time' => $currentTime,
            'allowable_time' => $allowableTime
        ]);

        return $application->office->is_chatbot_office
            && $this->getAutoAssignGlobalSetting()->setting_value
            && $allowableTime;
    }

    public function isAllowableTime($startTime, $endTime, $currentTime)
    {
        if ($startTime < $endTime) {
            return $currentTime->gt($startTime) && $currentTime->lt($endTime);
        }
        if ($startTime > $endTime) {
            return ($currentTime->gt($startTime) || $currentTime->lt($endTime));
        }

        return false;
    }
}
