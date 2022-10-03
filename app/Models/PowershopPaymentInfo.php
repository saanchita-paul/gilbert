<?php

namespace App\Models;

use App\Notifications\NotificationChannels\LogChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

/**
 *
 */
class PowershopPaymentInfo extends Model
{
    use HasFactory, Notifiable;

    /**
     *
     */
    const STATUS_PENDING = 1;
    /**
     *
     */
    const STATUS_VERIFIED = 2;
    /**
     *
     */
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

    /**
     * @var string[]
     */
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


    /**
     * @param $driver
     * @param $notification
     * @return mixed
     */
    public function routeNotificationForMail($driver, $notification = null)
    {
        return $this->customer_email;
    }

    /**
     * @return mixed|string
     */
    public function routeNotificationForTwilio()
    {
        $phone_number = $this->customer_phone;
        if (substr($phone_number, 0, 2) === '04') $phone_number = '+614' . substr($phone_number, 2);
        return $phone_number;
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function canReceiveAlphanumericSender()
    {
        return config('twilio-notification-channel.enable_alpha_sender');
    }

    /**
     * @param string|null $val
     * @return string|null
     */
    public function encrypt(?string $val): ?string
    {
        if(empty($val)) {
            return $val;
        }

        try {
            return Crypt::encryptString($val);
        } catch (\Exception $exception) {
            \Log::channel('pxpay')->error($exception);
            return $val;
        }
    }

    /**
     * @param string|null $val
     * @return string|null
     */
    public function decrypt(?string $val): ?string
    {
        if(empty($val)) {
            return $val;
        }
        try {
            return Crypt::decryptString($val);
        } catch (\Exception $exception) {
            \Log::channel('pxpay')->error($exception);
            return "raw_$val";
        }
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxCallbackResultAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxCardTypeAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxCardNumberAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxCardExpireDateAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxCardHolderNameAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPxDpsBillingIdAttribute($value)
    {
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxCallbackResultAttribute($value)
    {
        $this->attributes['px_callback_result'] = $this->encrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxCardTypeAttribute($value)
    {
        $this->attributes['px_card_type'] = $this->encrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxCardNumberAttribute($value)
    {
        $this->attributes['px_card_number'] = $this->encrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxCardExpireDateAttribute($value)
    {
        $this->attributes['px_card_expire_date'] = $this->encrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxCardHolderNameAttribute($value)
    {
        $this->attributes['px_card_holder_name'] = $this->encrypt($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setPxDpsBillingIdAttribute($value)
    {
        $this->attributes['px_dps_billing_id'] = $this->encrypt($value);
    }
}
