<?php


namespace App\Listeners\Agency;


use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Agency\SubmittedLeadNote;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePlanNoteListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle(SubmitApplicationEvent $event)
    {
        $user = $event->options['auth_user'];
        $servicesId = $event->options['services_id'];
        $existLead = ConnectionApplication::findOrFail($event->applicationId);
        $noteService = new SubmittedLeadNote($existLead, $user, $servicesId);
        $noteService->addSubmittedNote();
        info('new note is created for lead id'. $event->applicationId, ['connection_application_id'=>$event?->applicationId]);
    }

}
