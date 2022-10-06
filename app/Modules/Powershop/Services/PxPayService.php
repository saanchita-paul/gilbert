<?php

namespace Powershop\Services;

use App\Models\PowershopPaymentInfo;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class PxPayService
{
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $pxPayUser;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $pxPayKey;

    /**
     *
     */

    public function __construct()
    {
        $this->pxPayUser = config('powershop.px_pay_user');
        $this->pxPayKey = config('powershop.px_pay_key');
    }



    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getRedirectUrl(string $txnId): string
    {
        $paymentInfo = PowershopPaymentInfo::query()->where(['px_txn_id' => $txnId])->firstOrFail();

        $data = $this->generatePaymentData($paymentInfo);


        $xml = $this->toXml($data, 'GenerateRequest');

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $xml,
        ];

        $url = config('powershop.px_pay_url');

        $response = $client->request('POST', $url, $options);

        $body = $response->getBody()->getContents();
        $uri = (string) simplexml_load_string($body)->URI ?? null;
        if (!empty($uri)) {
            return $uri;
        } else {
            throw new Exception("PxPay Failed: " . json_encode($body));
        }
    }

    /**
     * @param $info
     * @return array
     */
    private function generatePaymentData($info): array
    {
        return [
            'PxPayUserId' => $this->pxPayUser,
            'PxPayKey' => $this->pxPayKey,
//            'TxnType' => 'Purchase',
            'TxnType' => $info->px_transaction_type,
            'AmountInput' => $info->px_amount,
            'CurrencyInput' => $info->px_currency_type,
            'MerchantReference' => 'Hood',
            'TxnData1' => $info->customer_full_name,
            'TxnData2' => $info->phone,
//            'TxnData3' => 'kaka',
            'EmailAddress' => $info->customer_email,
            'TxnId' => $info->px_txn_id,
            'EnableAddBillCard' => $info->px_is_enable_billing,
            'RecurringMode' => $info->px_recurring_mode,
            'UrlSuccess' => config('app.url') . '/powershop/payment/success?txn_id=' . $info->px_txn_id,
            'UrlFail' => config('app.url') . '/powershop/payment/failed?txn_id=' . $info->px_txn_id,
            'UrlCallback' => config('app.url') . '/powershop/payment/callback?txn_id=' . $info->px_txn_id,
        ];
    }


    /**
     * @param $array
     * @param $root
     * @return string
     */
    public function toXml($array, $root): string
    {

        $xml = "<$root>";
        foreach ($array as $key => $value) {
            $xml .= "<$key>$value</$key>";
        }
//    while (list($prop, $val) = each($arr))
//        $xml .= "<$prop>$val</$prop>" ;

        $xml .= "</$root>";
        return $xml;
    }

    /**
     * @throws GuzzleException
     */
    public function getPaymentDetails(string $key): array
    {
        $array = [
            'PxPayUserId' => config('powershop.px_pay_user'),
            'PxPayKey' => config('powershop.px_pay_key'),
            'Response' => $key,
        ];

        $xml = $this->toXml($array, 'ProcessResponse');
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $xml,
        ];
        $client = new Client();
        $url = config('powershop.px_pay_url');

        $response = $client->request('POST', $url, $options);
        $body = $response->getBody()->getContents();
        $res = json_decode(json_encode(simplexml_load_string($body)), true);

        return [
            'px_callback_result' => $key,
            "px_card_type" => data_get($res, 'CardName'),
            "px_card_number" => data_get($res, 'CardNumber'),
            "px_card_expire_date" => data_get($res, 'DateExpiry'),
            "px_card_holder_name" => data_get($res, 'CardHolderName'),
            "px_dps_billing_id" => data_get($res, 'DpsBillingId'),
            "px_response_text" => data_get($res, 'ResponseText'),
            "px_response_text_desc" => data_get($res, 'CardHolderResponseDescription'),
//            "preferred" => true,
            'status' => (int) data_get($res, 'Success')
                ? PowershopPaymentInfo::STATUS_VERIFIED
                : PowershopPaymentInfo::STATUS_REJECTED
        ];
    }

    /**
     * @param string $txnId
     * @return PowershopPaymentInfo
     */
    private function getPaymentInfo(string $txnId)
    {
        return PowershopPaymentInfo::query()->where(['px_txn_id' => $txnId])->firstOrFail();
    }
    /**
     * @throws GuzzleException
     */
    public function handleCallback(array $response)
    {
        $key = data_get($response, 'result');

        $paymentInfo =  $this->getPaymentInfo(data_get($response, 'txn_id'));
        $details = $this->getPaymentDetails($key);
        foreach (array_keys($details) as $key) {
            $paymentInfo->$key = $details[$key];
        }

        if ($paymentInfo->status == PowershopPaymentInfo::STATUS_VERIFIED){
            $paymentInfo->verified_at = now();
        }
        if ($paymentInfo->status == PowershopPaymentInfo::STATUS_REJECTED){
            $paymentInfo->rejected_at = now();
        }
        $paymentInfo->save();

        return $paymentInfo;
    }

    /**
     * @param array $response
     * @return PowershopPaymentInfo
     */
    public function handleFailed(array $response)
    {
        $paymentInfo =  $this->getPaymentInfo(data_get($response, 'txn_id'));
        $paymentInfo->rejected_at = now();
        $paymentInfo->save();
        return $paymentInfo;
    }

    /**
     * @param array $response
     *
     * @return string
     */
    public function handleSuccess(array $response)
    {
        $paymentInfo =  $this->getPaymentInfo(data_get($response, 'txn_id'));
        $paymentInfo->verified_at = now();
        $paymentInfo->save();
        return $paymentInfo->customer_full_name;
    }

}

