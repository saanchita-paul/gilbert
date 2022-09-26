<?php

namespace App\Contracts\SMS;

/**
 *
 */
interface SMSManagerInterface
{
    /**
     * @param SMSModel $sms
     * @return void
     */
    public function send(SMSModel $sms): void;
}
