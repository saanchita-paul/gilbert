<?php

namespace App\Services\Logger;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ErrorLogNotification;

class ErrorLogService
{
    public static function send(string $text, array $emailList)
    {
        try {
            $emails = (new self())->getEmailList($emailList);
            if($emails !== null || $emails !== []) {
                Notification::route('mail', $emails)
                    ->notify(new ErrorLogNotification($text));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private function getEmailList(array $emailList): array
    {
        if (empty($emailList) && env('ERROR_LOG_EMAILS')) {
            return explode(',', env('ERROR_LOG_EMAILS'));
        }
        return $emailList;
    }
}
