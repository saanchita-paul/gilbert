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

        if ($getContactByEmailData['exists'] === true) {
            $responseData = $getContactByEmailData['body'];
            $hubspotId = $responseData['vid'];

            $existingApp = $hubspotContactService->getOldApplicationData($hubspotId);
            if ($existingApp) {
                $oldAppHubspotService = new HubspotContactService($existingApp->id);
                $oldAppHubspotService->setOldHubspotFlag();
                $oldAppHubspotService->saveHistoricalData($responseData);
            }
            
            $hubspotContactService->setContactId($hubspotId);
            $hubspotContactService->update();
        } else {
            $hubspotContactService->create();
        }
    }

}
