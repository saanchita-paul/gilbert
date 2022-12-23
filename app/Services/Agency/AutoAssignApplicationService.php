<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
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

        Log::info('Auto assign application condition: ', [
            'auto_assign_condition' => $this->isAutoAssignable($application)
        ]);

        if (!$this->isAutoAssignable($application)) {
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
    private function isAutoAssignable($application)
    {
        $timeSlot = $application->office->timeSlots()->first();

        $currentTime = Carbon::now()->timezone(TimeZoneService::getTimeZoneArea());

        $allowableTime = false;

        // Check if current time is in time slot
        if (
            $timeSlot->start_time > $timeSlot->start_time
            && $currentTime->gt($timeSlot->start_time)
            && $currentTime->lt($timeSlot->end_time)
        ) {
            Log::info('Condition 1 : $startTime > $endTime && $currentTime > $startTime && $currentTime < $endTime');
            Log::info('Auto assign application: ', [
                'start_time' => $timeSlot->start_time,
                'end_time' => $timeSlot->end_time,
                'current_time' => $currentTime->format('Y-m-d H:i:s'),
                'condition' => $timeSlot->start_time > $timeSlot->start_time
                    && $currentTime->gt($timeSlot->start_time)
                    && $currentTime->lt($timeSlot->end_time)
            ]);
            $allowableTime = true;
        } elseif (
            $timeSlot->start_time > $timeSlot->end_time
            && (
                $currentTime->gt($timeSlot->start_time)
                || $currentTime->lt($timeSlot->end_time)
            )
        ) {
            Log::info('Condition 2: $startTime > $endTime && $currentTime > $startTime && $currentTime < $endTime');
            Log::info('Auto assign application: ', [
                'start_time' => $timeSlot->start_time,
                'end_time' => $timeSlot->end_time,
                'current_time' => $currentTime->format('H:i:s'),
                'condition' => $timeSlot->start_time > $timeSlot->start_time
                    && $currentTime->gt($timeSlot->start_time)
                    && $currentTime->lt($timeSlot->end_time)
            ]);
            $allowableTime = true;
        } else {
            Log::info('Condition 3: Not in condition 1 and 2');
            Log::info('Auto assign application: ', [
                'start_time' => $timeSlot->start_time,
                'end_time' => $timeSlot->end_time,
                'current_time' => $currentTime->format('H:i:s'),
                'condition' => false
            ]);
            $allowableTime = false;
        }

        Log::info('AutoAssignApplicationService: Time slot: ', $timeSlot->toArray());

        Log::info('AutoAssignApplicationService: Allowable time: ', [
            'start_time' => $timeSlot->start_time,
            'end_time' => $timeSlot->end_time,
        ]);

        Log::info('AutoAssignApplicationService: Is Allowable Time: ', [
            'current_date_time' => Carbon::now()->timezone(TimeZoneService::getTimeZoneArea())
                ->format('Y-m-d H:i:s'),
            'current_day' => date('l'),
            'is_allowable_time' => $allowableTime
        ]);

        Log::info('AutoAssignApplicationService: Is auto assignable: ', [
            'global_setting' => $this->getAutoAssignGlobalSetting()->toArray(),
            'is_auto_assignable_global_flag' => $this->getAutoAssignGlobalSetting()->setting_value,
            'is_ofc_auto_assignable' => $application->office->is_chatbot_office,
            'is_allowable_time' => $allowableTime,
            'is_auto_assignable' => $this->getAutoAssignGlobalSetting()->setting_value
                && $application->office->is_chatbot_office
                && $allowableTime
        ]);

        return $application->office->is_chatbot_office
            && $this->getAutoAssignGlobalSetting()->setting_value
            && $allowableTime;
    }
}
