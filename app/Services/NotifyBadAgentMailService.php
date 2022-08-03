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
        $isNotOnboarded = false;
        $isNotActive = false;

        if (!$isNotExist) {
            $agentProf = $application->createdBy;
            $agentUser = $agentProf->user;
            $isNotOnboarded = empty($agentUser->password);
            $isNotActive = !$agentUser->is_active;
        }

        if($isNotExist || $isNotOnboarded || $isNotActive){
            $mail = new AgentNotFoundMail($application->id, $submitType, $agencyName, $officeName, $agentEmail, $isNotOnboarded, $isNotActive);
    
            $emails = explode(',', config('support_email.agent_not_found'));
            foreach ($emails as $recipient) {
                Mail::to($recipient)->queue($mail);
            }
        }
    }
}
