<?php

namespace App\Services;


use App\Models\ApplicationEvent;

class ApplicationEventService
{
    /**
     * save application event
     */
    public function saveApplicationEvent(array $event)
    {
        $event['event_type'] = ApplicationEvent::TWIDDLE_SMS_CLICK;
        $checkAppExists = ApplicationEvent::query()->where('app_id', $event['app_id'])->exists();
        if (!$checkAppExists) {
            return ApplicationEvent::query()->create($event);
        }
    }
}
