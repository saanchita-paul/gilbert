<?php

namespace App\Services\hubspot;

use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Collection;

class HubspotHandlerService
{
    private array|Collection|ConnectionApplication $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
    }

    public function handle()
    {
        try {
            $hubspotContactService = new HubspotContactService($this->application->id);
            if (empty($this->application->hubspot_contact_id)) {
                $getContactByEmailData = $hubspotContactService->getContactByEmail($this->application->email);

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
            } else {
                $hubspotContactService->update();
            }
        } catch (\Exception $e) {
            \Log::error('Hubspot Handler Error (view context)', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
