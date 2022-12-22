<?php

namespace App\Listeners;

use App\Services\Application\ApplicationLockUnlockService;

class ConnectionApplicationClosedOrEscalatedListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        try {
            $service = new ApplicationLockUnlockService($event->applicationId);
            $service->closeOrEscalatedChatbot();
            \Log::info('Application closed or escalated successfully.');
        } catch (\Exception $e) {
            \Log::error('Closed/escalated chatbot change listener - ' . $e->getMessage());
            \Log::error('Closed/escalated chatbot change listener - ' . $e->getTraceAsString());
        }
    }
}
