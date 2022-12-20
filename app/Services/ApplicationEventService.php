<?php

namespace App\Services;


use App\Models\ApplicationEvent;

class ApplicationEventService
{
    /**
     * save application event
     */
    public function saveApplicationEvent($event)
    {
        $event['event_type'] = $event['event_type'] ? ApplicationEvent::EVENT_TYPE[$event['event_type']] : '';
        $checkAppExists = ApplicationEvent::query()->where('app_id', $event['app_id'])->exists();
        if (!$checkAppExists) {
            return ApplicationEvent::query()->create($event);
        }
    }
}
