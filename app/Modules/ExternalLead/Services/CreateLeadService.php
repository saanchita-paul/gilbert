<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;

class CreateLeadService
{
    public function create(ExternalSource $externalSource, array $data)
    {
        dd($data);
        // 1. save raw data
        SaveRawData::dump($externalSource->id, $data);
        // 2. save connection application data
        // 3. notify Georgie if missing agent email in Hood
        // 4. save identification
        // 5. save authorized person
        // 6. dispatch NotifyAgentAfterLeadCreation
        // 7. dispatch CreateApplicationEvent
    }
}
