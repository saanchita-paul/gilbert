<?php

namespace App\Plugins\AWS\SMS;

use App\Contracts\SMS\SMSManagerInterface;
use App\Contracts\SMS\SMSModel;
use Aws\Sns\SnsClient;
use AWS;

/**
 *
 */
class SMSManagerAWS implements SMSManagerInterface
{

    /**
     * Sending sms using aws sns sms.
     *
     * @param SMSModel $sms
     *
     * @return void
     */
    public function send(SMSModel $sms): void
    {
        if ((boolean) env('IS_ACTIVE_SMS', false)) {
            /** @var SnsClient $client */
            $client = AWS::createClient('sns');
            $res = $client->publish([
                'Message' => $sms->getContent(),
                'PhoneNumber' => $sms->getPhoneNumber(),
                "SMSType" => config('aws.sms_type'),
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => config('aws.sender_id')
                    ]
                ],
            ]);
            \Log::info("SMS LOGGING", array_merge(
                ['phone' => $sms->getContent(), 'message' => $sms->getContent()],
                $res->toArray()
            ));
        } else {
            \Log::info(
                'SMS NOT ACTIVATED'
                .' ['.$sms->getPhoneNumber() . '] -> ' . $sms->getContent()
            );
        }
    }
}
