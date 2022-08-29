<?php

namespace Powershop\Notifications;

use App\Models\ConnectionApplication;
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
    public function __construct(private string $name, private string $url)
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
            ->view('email.powershop.px_verification', [
                'paymentUrl' => $this->url,
                'name' => $this->name
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
