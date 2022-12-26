<?php

namespace App\Notifications;

use App\Models\ConnectionApplication;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FetchMirnNmiNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;
    private $applicationId;
    private $message;
    private $application;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($applicationId, $message = null)
    {
        $this->applicationId = $applicationId;
        $this->message = $message ?? 'MIRN and NMI fetched successfully!';
        $this->application = ConnectionApplication::find($this->applicationId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'connection_application_id' => $this->applicationId,
            'message' => $this->message,
            'mirn' => $this->application->mirn,
            'nmi' => $this->application->nmi,
            'loading_address_info' => $this->application->loading_address_info,
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('fetchMirnNmi.' . $this->applicationId);
    }

}
