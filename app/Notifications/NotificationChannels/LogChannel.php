<?php

namespace App\Notifications\NotificationChannels;

use Illuminate\Notifications\Notification;

class LogChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification)
    {
        $message = $notification->toLog($notifiable);

        \Log::info('LogNotificationChannel');
        \Log::info($message);
        \Log::info('LogNotificationChannel');
    }
}
