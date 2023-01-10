<?php

namespace App\Services;


use App\Models\ApplicationEvent;

class ApplicationEventService
{
    /**
     * save application event
     *
     */
    public function saveApplicationEvent($event)
    {
        $data = [
            'app_id' => $event['app_id'],
            'event_type' => $event['event_type']
        ];

        $allowable = $this->isAllowableType($data['event_type']);

        if (!$allowable) {
            throw new \Exception("ApplicationEventService: Event type " . $data['event_type'] . " not allowed!");
        }

        return ApplicationEvent::query()->updateOrCreate($data);
    }

    /**
     * check allowable event type
     * @param $eventType
     * @return bool
     */
    private function isAllowableType($eventType): bool
    {
        $allowedEventType = ['twiddle_sms_click'];

        if (!in_array($eventType, $allowedEventType)) {
            return false;
        }
        return true;
    }
}
