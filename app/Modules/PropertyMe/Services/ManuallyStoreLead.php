<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\Office;
use Exception;
use PropertyMe\Services\SaveContacts;

class ManuallyStoreLead
{
    /**
     * Saving application from leads data
     *
     * @param int $officeId
     * @param array $leadsData
     *
     * @return array
     *
     * @throws Exception
     */
    public static function run(int $officeId, array $leadsData): array
    {
        $office = Office::findOrFail($officeId);

        if (empty($office->property_me_refresh_token)) {
            throw new Exception("Office '{$office->name}' is not connected to PropertyMe");
        }

        $service = new SaveContacts($office->property_me_refresh_token);
        $leads = $service->setLeads($leadsData)
            ->loadTenancies()
            ->createLead()
            ->getSavedLeads();
        $tenancies = $service->getTenancies();


        $saveService = new SaveToConnectionApplication($office, $tenancies);

        $savedApplications = [];
        foreach ($leads as $lead) {
            $savedApplications[] = $saveService->run($lead);
        }

        return $savedApplications;
    }
}
