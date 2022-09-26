<?php

namespace App\Notifications\NotificationChannels;

use App\Contracts\SMS\ShouldNotifyViaSMS;
use App\Contracts\SMS\SMSManagerInterface;
use Illuminate\Notifications\AnonymousNotifiable;

/**
 *
 */
class SMSNotificationChannel
{
    /**
     * @param SMSManagerInterface $manager
     */
    public function __construct(private SMSManagerInterface $manager) {}

    /**
     * @param AnonymousNotifiable $notifiable
     * @param ShouldNotifyViaSMS $viaSMS
     * @return void
     */
    public function send(AnonymousNotifiable $notifiable, ShouldNotifyViaSMS $viaSMS)
    {
        $this->manager->send($viaSMS->toSMS($notifiable));
    }
}
