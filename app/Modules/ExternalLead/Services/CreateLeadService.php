<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use ExternalLead\Models\ExternalLeadApiLog;

class CreateLeadService
{
    public function create(ExternalSource $externalSource, array $data)
    {
        $sourceType = $externalSource->source_type;
        info("CreateLeadService:create $sourceType (refer context for data)", $data);

        $newAppService = new CreateAppService();
        $newApp = $newAppService->create($externalSource, $data);

        $dump = ExternalLeadApiLog::findOrFail($data['dump_id']);
        $dump->connection_application_id = $newApp->id;
        $dump->agency_name = $newApp->agency->name ?? null;
        $dump->save();

        return $newApp;
    }
}
