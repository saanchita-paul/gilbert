<?php

namespace Powershop\Notifications;

use App\Models\ConnectionApplication;
use App\Notifications\NotificationChannels\LogChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PxPaymentInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private ConnectionApplication $connectionApplication;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private string $name, private string $url, private string $channel)
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
        return  match ($this->channel) {
            'email' => ['mail'],
            'sms' =>  [LogChannel::class]
        };
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Lead Submitted")
            ->view('powershop.email_px_invite', [
                'paymentUrl' => $this->url,
                'name' => $this->name
            ]);
    }

    public function toLog($notifiable)
    {
        return json_encode($notifiable);
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
