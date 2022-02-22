<?php

namespace App\Services\Agency;

use App\Mail\WaterSumissionFailed;
use Illuminate\Support\Facades\Mail;
use App\Models\ConnectionApplication;
use App\Mail\WaterAddressValidationMail;

class WaterEmailService{

    public static function sendEmailWhenSubmissionFails($msg, $applicationId)
    {
        try {
            info("trying to send email");
            info($applicationId);
            $connectionApplication = ConnectionApplication::where('id' , $applicationId)->first();
            info("connection application , " , [$connectionApplication->id]);
            $dataToBeSent =  [
                'reason of failure' => $msg,
                'lead id'           => $connectionApplication->id,
            ];
            $emails =  explode( ',', config('water.support_emails'));
            info('emails' , $emails);
            foreach($emails as $recipient)
            {
                info('email sending to: ' , [ $recipient ]);
                Mail::to($recipient)->send(new WaterSumissionFailed($dataToBeSent));
            }
        } catch (\Exception $exception)
        {
            info("email sending failed", [
                'msg' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }

    public static function sendInvalidAddressWaterMail($leadInfo)
    {
        try {
            info("trying to send email water address");
            info('water variable log' , ['info3' => $leadInfo]);
            info($leadInfo);
            // $connectionApplication = ConnectionApplication::where('id' , $applicationId)->first();
            // info("connection application , " , [$connectionApplication->id]);
            // $dataToBeSent =  [
            //     'reason of failure' => 'send email',
            //     'lead id'           => 11,
            // ];
            $dataToBeSent = $leadInfo;
            $emails =  explode( ',', config('water.support_emails'));
            info('emails address issue' , $emails);
            foreach($emails as $recipient)
            {
                info('email sending to address: ' , [ $recipient ]);
                Mail::to($recipient)->send(new WaterAddressValidationMail($dataToBeSent));
            }
        } catch (\Exception $exception)
        {
            info("email sending failed", [
                'msg' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
