<?php


namespace App\Notifications;


use App\Models\ConnectionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAgentAboutLeadCreation extends Notification
{
    use Queueable;

    private ConnectionApplication $connectionApplication;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ConnectionApplication $connectionApplication)
    {
        //
        $this->connectionApplication = $connectionApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Lead Submitted")
            ->view('email.notify_agent', [
                'lead_info' => $this->prepareLeadData(),
            ]);
    }

    public function prepareLeadData()
    {
        $lead_info = $this->connectionApplication
            ->only(['id', 'first_name', 'middle_name', 'last_name', 'mirn', 'mni']);
        $lead_info['submitted_at'] = now();
        return $lead_info;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
