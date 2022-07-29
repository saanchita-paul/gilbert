<?php

namespace App\Notifications;

use App\Models\ConnectionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyToAgent extends Notification implements ShouldQueue
{
    use Queueable;

    private ConnectionApplication $connectionApplication;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Lead Submitted")
            ->view('template.email', [
                'name' => 'sanchita',
                'agentName'=> 'Hood AI',
                'fullName' => 'test',
                'address' => 'Road 31, House #374, Mohakhali, Dhaka',
                'date' => '06-20-2022'

            ]);
    }




    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
