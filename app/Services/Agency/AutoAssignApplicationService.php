<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\Setting;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Support\Facades\Log;

class AutoAssignApplicationService
{
    /**
     * Auto assign application to chatbot
     * @param $application
     * @return void
     * @throws \Exception
     */
    public function assignApplication($application)
    {
        try {
            $chatbotUser = $this->getChatbotUser();
            $applicationService = new ApplicationService();
            if ($chatbotUser && $this->isAutoAssignable($application)) {
                $applicationService->assignUser($chatbotUser->profile_id, $application->id);
            }

            if ($application->tenancy_type != ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
                $autoSubmitService = new WaterAutoSubmitService($application->id);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Get chatbot user
     * @return User|null
     */
    private function getChatbotUser()
    {

        return User::whereHas('roles', function ($query) {
            $query->where('name', 'hood_chatbot_user');
        })->first();
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
        return $application->office->is_chatbot_office && $this->getAutoAssignGlobalSetting()->setting_value;
    }
}
