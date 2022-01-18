<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentNotFoundMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $lead_info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $lead_info)
    {
        $this->lead_info = $lead_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.agency.agency_not_found')
            ->subject("OurPropery lead submission failed")
            ->with([
                'lead_info' => $this->lead_info,
            ]);;
    }
}
