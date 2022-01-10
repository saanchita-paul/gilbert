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
        info('submitted note created ', ['connection_application_id'=>$event?->id]);
        $existLead = ConnectionApplication::findOrFail($event->id);
        $noteService = new SubmittedLeadNote($existLead);
        $noteService->addSubmittedNote();
        info('new note is created for lead id'. $event->id, ['connection_application_id'=>$event?->id]);
    }

}
