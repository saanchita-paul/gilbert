<?php

namespace Powershop\Services;

use App\Jobs\GilbertToChatbotSyncJob;
use App\Models\ConnectionApplication;
use App\Models\PowershopPaymentInfo;
use Illuminate\Support\Str;
use Powershop\Notifications\PxPaymentInviteNotification;

class PaymentInfoService
{
    /**
     * @var PowershopPaymentInfo
     */
    private $paymentInfo;

    public function __construct(public int $appId)
    {
        $this->paymentInfo = PowershopPaymentInfo::query()
            ->where(['connection_application_id' => $this->appId])
            ->first();
    }

    public function getPaymentInfo()
    {

    }

    public function update(array $data)
    {
        if ($this->paymentInfo) {
            $this->paymentInfo->update($data);
        } else {
            $this->paymentInfo = new PowershopPaymentInfo();
            $this->paymentInfo->fill(array_merge(['connection_application_id' => $this->appId], $data));
            $this->paymentInfo->save();
        }

        GilbertToChatbotSyncJob::dispatch($this->appId);
        return $this->paymentInfo;
    }


    /**
     */
    private function updateInfoForInvite(): void
    {
        $lead = ConnectionApplication::findOrFail($this->appId);
        $txnId = Str::uuid()->toString();

        $data = [
            'status' => PowershopPaymentInfo::STATUS_PENDING,
            'invited_at' => now(),
//            'verified_at',
//            'rejected_at',
            'customer_full_name' => $lead->first_name . ' ' . $lead->last_name,
            'customer_email' => $lead->email,
            'customer_phone' => $lead->phone,
            'px_transaction_type' => 'Validate',
            'px_amount' => 0,
            'px_currency_type' => 'AUD',
            'px_txn_id' => $txnId,
            'px_is_enable_billing' => 1,
//            'px_redirect_url' => $url,
            'px_recurring_mode' => 'recurringinitial',
//            'px_callback_result',
//            'px_response_text',
//            'px_card_type',
//            'px_card_number',
//            'px_card_expire_date',
//            'px_card_holder_name',
//            'px_dps_billing_id'
        ];

        $this->update($data);
    }


    /**
     * @param string $channel
     * @return PowershopPaymentInfo
     */
    public function inviteCustomer(string $channel): PowershopPaymentInfo
    {
        $this->updateInfoForInvite();

        $url = config('app.url') . '/powershop/payment/accept-invite/' . $this->paymentInfo->px_txn_id;

        $this->paymentInfo->notify(new PxPaymentInviteNotification(
            $this->paymentInfo->customer_full_name,
            $url,
            strtolower($channel)
        ));


        return $this->paymentInfo;
    }
}
