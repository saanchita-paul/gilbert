<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserInviteNotification;

class SendUserInviteService
{
    private UserInvitation $invitation;

    public function __construct(public User $user)
    {
    }

    public function run()
    {
        $this->createInvite()->notify();
    }

    /**
     *
     * @return $this
     */
    private function createInvite()
    {
        $this->invitation = new  UserInvitation();
        $this->invitation->fill([
            'token' => \Str::random(90),
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'valid_till' => now()->addDays(3)->toDateTime(),
        ])->save();

        return $this;
    }

    private function notify()
    {
        $this->user->notify(new UserInviteNotification($this->invitation));
    }
}
