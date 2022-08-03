<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentNotFoundMail extends Mailable
{
    use Queueable, SerializesModels;

    
    private array $leadInfo;
    private bool $isNotOnboarded;
    private bool $isNotActive;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $connection_application_id, string $submitType, string $agencyName, string $officeName, string $agentEmail, bool $isNotOnboarded = false, bool $isNotActive = false)
    {
        $this->leadInfo = [
            'App ID' => $connection_application_id,
            'Lead Source' => $submitType,
            'Agency Name' => $agencyName,
            'Office Name' => $officeName,
            'Agent Email' => $agentEmail,
        ];

        $this->isNotOnboarded = $isNotOnboarded;
        $this->isNotActive = $isNotActive;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Lead Submit Failed - Agent not found";
        $reason = "This application has an agent email that does not exist in Gilbert!";

        if ($this->isNotOnboarded) {
            $subject = "Lead Submit Failed - Agent is not fully onboarded";
            $reason = "This application has an existing agent email in Gilbert but is not fully onboarded";
        } else if ($this->isNotActive) {
            $subject = "Lead Submit Failed - Agent inactive";
            $reason = "This application has an existing agent email in Gilbert but is inactive";            
        }

        return $this->view('email.agent_not_found')
            ->subject($subject)
            ->with([
                'lead_info' => $this->leadInfo,
                'reason' => $reason,
            ]);
    }
}
