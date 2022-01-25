<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Builder;

class SyncAgentService
{
    /**
     * @var array
     */
    private array $tenancies;
    public function sync()
    {
        $leads = $this->getApplications();

        foreach ($leads as $lead) {
            $token = $lead->office->property_me_refresh_token;
            $contactAPI = new FetchContactAPI($token);
            $this->fetchTenancies($contactAPI, $token);

            $lotId = $this->getLotId(data_get($lead, 'propertyMeLead.lead_id'), $token);

            if ($lotId) {
                try {
                    $lotMembers = $contactAPI->fetchTLotMembers($lotId);
                    $agentEmail = data_get($lotMembers, 'RegisteredEmail');

                    $agent = AgentProfile::query()
                        ->whereHas('user', fn(Builder $b) => $b->where('email', $agentEmail))
                        ->first();

                    $lead->created_by = $agent?->id;

                    $lead->save();
                } catch (\Exception $exception) {
                    \Log::error('AgentSyncError', [$exception->getMessage()]);
                }
            }
        }
    }

    private function fetchTenancies(FetchContactAPI $contactAPI, string $token)
    {
        if (!isset($this->tenancies[$token])) {
            $this->tenancies[$token] = $contactAPI->fetchTenancies()->getTenancies();
        }
    }

    private function getApplications()
    {
        return ConnectionApplication::query()
            ->with('office', 'propertyMeLead')
            ->whereHas('office', fn(Builder $b) => $b->whereNotNull('property_me_refresh_token'))
            ->where('source', ConnectionApplication::SOURCE_PROPERTY_ME)
            ->whereNull('created_by')
            ->where('id', '>', 194) # this condition is for testing purpose only.
            ->get();
    }

    /**
     * @param string $contactId
     * @param string $token
     * @return string|null
     */
    private function getLotId(string $contactId, string $token): ?string
    {
        $data = $this->tenancies[$token];

        return collect($data)
            ->filter(fn($value) => data_get($value, 'ContactId') === $contactId)
            ->pluck('LotId')
            ->first();
    }
}
