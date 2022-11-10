<?php
 
namespace MRI\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class NotifyFetchFailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $serviceClassName;

    public $exceptionData;
 
    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct($serviceClassName, $exceptionData)
    {
        $this->serviceClassName = $serviceClassName;
        $this->exceptionData = $exceptionData;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("MRI Fetch Job Failed")
                    ->view('email.mri_fetch_fail');
    }
}