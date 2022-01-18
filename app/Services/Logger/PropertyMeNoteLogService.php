<?php

namespace App\Services\Logger;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PropertyMeInvalidNoteNotification;

class PropertyMeNoteLogService
{
    public static function send(array $data, array $emailList)
    {
        try {
            $emails = (new self())->getEmailList($emailList);
            if($emails !== null || $emails !== []) {
                Notification::route('mail', $emails)
                    ->notify(new PropertyMeInvalidNoteNotification($data));
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
