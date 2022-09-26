<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class PowershopPaymentInfo extends Model
{
    use HasFactory, Notifiable;

    const STATUS_PENDING = 1;
    const STATUS_VERIFIED = 2;
    const STATUS_REJECTED= 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'connection_application_id',
        'status',
        'invite_token',
        'is_active',
        'estimated_elec_billing_cost',
        'estimated_elec_billing_period',
        'estimated_gas_billing_cost',
        'estimated_gas_billing_period',
        'invited_at',
        'verified_at',
        'rejected_at',
        'customer_full_name',
        'customer_email',
        'customer_phone',
        'px_transaction_type',
        'px_amount',
        'px_currency_type',
        'px_txn_id',
        'px_is_enable_billing',
        'px_redirect_url',
        'px_recurring_mode',
        'px_callback_result',
        'px_response_text',
        'px_card_type',
        'px_card_number',
        'px_card_expire_date',
        'px_card_holder_name',
        'px_dps_billing_id',
        'px_response_text_desc'
    ];

    protected $hidden = [
        'px_transaction_type',
        'px_amount',
        'px_currency_type',
        'px_txn_id',
        'px_is_enable_billing',
        'px_redirect_url',
        'px_recurring_mode',
        'px_callback_result',
//        'px_response_text',
        'px_card_type',
        'px_card_number',
        'px_card_expire_date',
        'px_card_holder_name',
        'px_dps_billing_id',
//        'px_response_text_desc'
    ];




    /**
     * @return BelongsTo
     */
    public function connecttion_application()
    {
        return $this->belongsTo(ConnectionApplication::class, 'connection_application_id');
    }


    public function routeNotificationForMail($driver, $notification = null)
    {
        return $this->customer_email;
    }

    public function routeNotificationForTwilio()
    {
        $phone_number = $this->customer_phone;
        if (substr($phone_number, 0, 2) === '04') $phone_number = '+614' . substr($phone_number, 2);
        return $phone_number;
    }
}
