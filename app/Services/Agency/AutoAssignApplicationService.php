<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\Setting;
use App\Models\User;
use App\Services\FastConnectService;
use App\Services\SettingService;
use App\Services\TimeZoneService;
use Carbon\Carbon;
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
            $result = $this->fetchMirnNmi($application);
            if ($result['mirn'] && $result['nmi']) {
                $this->assignUser($application);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    private function assignUser($application)
    {
        $chatbotUser = $this->getChatbotUser();
        $applicationService = new ApplicationService();
        if ($chatbotUser && $this->isAutoAssignable($application)) {
            $applicationService->assignUser($chatbotUser->profile_id, $application->id);
        }

        if ($application->tenancy_type != ConnectionApplication::TENANCY_TYPE_HOME_OWNER) {
            $autoSubmitService = new WaterAutoSubmitService($application->id);
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
        $timeSlot = $application->office->timeSlots()
            ->where('day', strtolower(date('l')))
            ->first();
        $allowableTime = Carbon::now()->timezone(TimeZoneService::getTimeZoneArea())
        ->isBetween($timeSlot->start_time, $timeSlot->end_time);
//        dd($allowableTime);
        return $application->office->is_chatbot_office && $this->getAutoAssignGlobalSetting()->setting_value;
    }

    private function fetchMirnNmi($application)
    {
        $result = [
            'mirn' => $application->mirn,
            'nmi' => $application->nmi
        ];
        if ($application->office->is_chatbot_office && (!$application->mirn || !$application->nmi)) {
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->searchAddress([], true, $application->id);
        }

        return $result;
    }
}
