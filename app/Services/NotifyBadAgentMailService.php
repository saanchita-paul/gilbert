<?php

namespace App\Services;

use App\Mail\AgentNotFoundMail;
use Illuminate\Support\Facades\Mail;
use App\Models\ConnectionApplication;

class NotifyBadAgentMailService
{
    public static function check(ConnectionApplication $application, string $submitType, string $agencyName, string $officeName, string $agentEmail)
    {
        $isNotExist = !$application->created_by;
        $isNotActive = false;

        if (!$isNotExist) {
            $agentProf = $application->createdBy;
            $agentUser = $agentProf->user;
            $isNotActive = !$agentUser->is_active;
        }

        if ($isNotExist || $isNotActive) {
            $mail = new AgentNotFoundMail($application->id, $submitType, $agencyName, $officeName, $agentEmail, $isNotActive);
            $emails = explode(',', config('support_email.agent_not_found'));
            foreach ($emails as $recipient) {
                if (!empty($recipient)) {
                    Mail::to($recipient)->queue($mail);
                }
            }
        }
    }
}
