<?php

namespace App\Contracts\SMS;

use Illuminate\Notifications\AnonymousNotifiable;

interface ShouldNotifyViaSMS
{
    /**
     * @param AnonymousNotifiable $notifiable
     *
     * @return SMSModel
     */
    public function toSMS(AnonymousNotifiable $notifiable): SMSModel;
}
