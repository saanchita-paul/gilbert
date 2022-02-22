<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaterAddressValidationMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $lead_info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $lead_info)
    {
        $this->lead_info = $lead_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.applications.water_address_validation_failed')
            ->subject("Water Address Validation Failed")
            ->with([
                'lead_info' => $this->lead_info,
            ]);;
    }
}
