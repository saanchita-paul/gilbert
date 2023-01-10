<?php

namespace MRI\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyNewOfficeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var array of MRI Office Names
     */
    public $newOffices;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $newOffices)
    {
        $this->newOffices = $newOffices;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("MRI New Offices")
                    ->view('email.mri_new_offices');
    }
}
