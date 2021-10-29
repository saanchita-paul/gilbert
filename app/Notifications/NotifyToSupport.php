<?php

namespace App\Notifications;

use DateTime;
use Illuminate\Bus\Queueable;
use App\Models\ConnectionApplication;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyToSupport extends Notification
{
    use Queueable;

    private ConnectionApplication $connectionApplication;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( ConnectionApplication $connectionApplication )
    {
        //
        $this->connectionApplication = $connectionApplication;
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
        
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        $lead_info = $this->connectionApplication
        ->only(['id', 'first_name',  'middle_name' , 'last_name' , 'mirn' , 'mni' ]);
        // $lead_info['submitted_at'] = DateTime::createFromFormat('F d, Y', now())->format('Y-m-d');
        $lead_info['submitted_at'] = now();
        info($this->connectionApplication);
        info($lead_info);
        $lead_info = $this->prepareLeadData();
        return (new MailMessage)
            ->subject("Hood registration invite")
            ->view('email.submit_application', [
                'lead_info' => $lead_info,
            ]);
    }

    public function prepareLeadData(){
        $lead_info = $this->connectionApplication
        ->only(['id', 'first_name',  'middle_name' , 'last_name' , 'mirn' , 'mni' ]);
        // $lead_info['submitted_at'] = DateTime::createFromFormat('F d, Y', now())->format('Y-m-d');
        $lead_info['submitted_at'] = now();
        info($this->connectionApplication);
        info($lead_info);
        return $lead_info;
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
