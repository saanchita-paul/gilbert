<?php

namespace App\Mail;

use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    private $profile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AgentProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.agency.invite_user')
            ->with([
                'profile' => $this->profile,
            ]);;
    }
}
