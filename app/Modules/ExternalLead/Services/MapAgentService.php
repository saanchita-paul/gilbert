<?php

namespace ExternalLead\Services;

use App\Models\AgentProfile;
use App\Models\ExternalSource;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Services\NotifyBadAgentMailService;

class MapAgentService
{
    public function map(ExternalSource $source, ConnectionApplication $app, string $agentEmail = '')
    {
        $defaultOffice = $source->defaultOffice;
        $officeId = $defaultOffice->id;
        $agencyId = $defaultOffice->agency_id;
        $agentId = null;
        try {
            $agentExist = AgentProfile::whereHas(
                'user',
                fn(Builder $user) => $user->where('email', $agentEmail)
            )->first();
            if ($agentExist) {
                $agencyId = $agentExist->agency_id;
                $officeId = $agentExist->office_id;
                $agentId = $agentExist->id;
            }
            $app->agency_id = $agencyId;
            $app->office_id = $officeId;
            $app->created_by = $agentId;
            $app->save();

            NotifyBadAgentMailService::check(
                $app,
                $source->display_type_name,
                $defaultOffice->agency->name,
                $defaultOffice->name,
                $agentEmail
            );
            return $app;
        } catch (\Exception $exception) {
            Log::error('ExternalLead MapAgentService Fail');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            // throw $exception;
        }
    }
}
