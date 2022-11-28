<?php

namespace MRI\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMissingDetailsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $missingData;

    /**
     * Create a new message instance.
     *
     * @param array
     *  [
     *      'APP_ID' => [
     *          'application title',
     *          'application first_name',
     *      ],
     *  ]
     * @return void
     */
    public function __construct(array $missingData)
    {
        $this->missingData = $missingData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("MRI Application Missing Details")
                    ->view('email.mri_missing_details');
    }
}
