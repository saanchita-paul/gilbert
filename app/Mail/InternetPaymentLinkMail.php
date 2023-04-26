<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternetPaymentLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    public $subject = "Thanks for choosing Goodtel!";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->data['customer_name'] ?? 'Customer';
        $paymentUrl = $this->data['payment_url'] ?? '#';
        return $this->view('email.internet-payment-link-mail', [
            'name' => $name,
            'paymentUrl' => $paymentUrl,
            'charity' => $this->data['charity'],
            'service_address' => $this->data['service_address'],
            'plan_name' => $this->data['plan_name'],
            'modem_type' => $this->data['modem_type']
        ]);
    }
}
