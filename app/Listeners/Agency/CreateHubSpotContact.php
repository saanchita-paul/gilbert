<?php

namespace App\Listeners\Agency;

use App\Events\Agency\CreateApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateHubSpotContact implements ShouldQueue
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
     * @param CreateApplicationEvent $event
     * @return void
     * @throws \Exception
     */
    public function handle(CreateApplicationEvent $event)
    {
        $existLead = ConnectionApplication::findOrFail($event->applicationId);
        $hubspotContactService = new HubspotContactService($event->applicationId);

        $getContactByEmailData = $hubspotContactService->getContactByEmail($existLead->email);

        info("<----- i am get getContactByEmailData without------>", $getContactByEmailData);
//        info("<----- i am get body getContactByEmailData ------>", [$getContactByEmailData['body']]);

        if ($getContactByEmailData['exists'] === true ) {
//            $existLead->update(['hubspot_contact_id' => $getContactByEmailData['body']->vid]);
            $hubspotContactService->update();
        } else {
            info("<----- i am not exists------>", [$getContactByEmailData['exists']]);
            $hubspotContactService->create();
        }
    }

}
