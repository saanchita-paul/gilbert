<?php

namespace App\Services\PowerShop;

use App\Services\TimeZoneService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

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
     * @throws \Exception
     */
    public function getRedirectUrl(): string
    {
        $data = $this->generatePaymentData();
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
        $uri = (string ) simplexml_load_string($body)->URI ?? null;
        if (!empty($uri)) {
            return $uri;
        } else {
            throw new \Exception("PxPay Failed: " . json_encode($body));
        }
    }

    /**
     * @return array
     */
    private function generatePaymentData(): array
    {
        return [
            'PxPayUserId' => $this->pxPayUser,
            'PxPayKey' => $this->pxPayKey,
//            'TxnType' => 'Purchase',
            'TxnType' => 'Validate',
            'AmountInput' => 0.0,
            'CurrencyInput' => 'AUD',
            'MerchantReference' => 'Purchase Example',
            'TxnData1' => 'Atikur Rahman',
            'TxnData12' => '0211111111',
            'EmailAddress' => 'a@gmail.com',
            'TxnId' => Str::uuid()->toString(),
            'EnableAddBillCard' => 1,
            'RecurringMode' => 'recurringinitial',
            'UrlSuccess' => 'https://enk.leninsheikh.com/api/kaka/success',
            'UrlFail' => 'https://enk.leninsheikh.com/api/kaka/failed',
            'UrlCallback' => 'https://enk.leninsheikh.com/api/kaka/callback',
        ];
    }

    /**
     * @param $array
     * @param $root
     * @return string
     */
    public function toXml($array, $root): string
    {

        $xml  = "<$root>";
        foreach ($array as $key => $value) {
            $xml .= "<$key>$value</$key>" ;
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

        return  [
                "card_type" => data_get($res, 'CardName'),
                "masked_card_number" => data_get($res, 'CardNumber'),
                "expiry_date" => data_get($res, 'DateExpiry'),
                "cardholder_name" => data_get($res, 'CardHolderName'),
                "token" => data_get($res, 'DpsBillingId'),
                "terms_and_conditions_accepted_at" => now(TimeZoneService::getTimeZoneInt()),
                "preferred" => true
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function handleCallback(array $response)
    {
        $key = data_get($response, 'result');

        return [
            'paymentKey' => $key,
            'paymentDetails' => $this->getPaymentDetails($key),
        ];

    }
}

