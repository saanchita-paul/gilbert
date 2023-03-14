<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use App\Models\ConnectionApplication;

class CreateLeadService
{
    private ConnectionApplication $createdApp;

    public function create(ExternalSource $externalSource, array $data)
    {
        $sourceType = $externalSource->source_type;
        info("CreateLeadService:create $sourceType (refer context for data)", $data);
        $dump = SaveRawData::dump($externalSource->id, $data);

        $newAppService = new CreateAppService();
        $newApp = $newAppService->create($externalSource, $data);

        $dump->connection_application_id = $newApp->id;
        $dump->agency_name = $newApp->agency->name ?? null;
        $dump->save();

        $this->createdApp = $newApp;
        return $this->createdApp;
    }
}
