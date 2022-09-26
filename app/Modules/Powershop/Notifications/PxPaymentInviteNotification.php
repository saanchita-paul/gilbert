<?php

namespace Powershop\Notifications;

use App\Models\ConnectionApplication;
use App\Notifications\NotificationChannels\LogChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

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
            'sms' =>  [TwilioChannel::class],
            'log' => [LogChannel::class],
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

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content("Hello {$this->name},\n\nThank you for choosing Powershop with Hood! As we mentioned, please provide your payment details by click on the link bellow.\n{$this->url}\n\nHOOD Support Team");
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
