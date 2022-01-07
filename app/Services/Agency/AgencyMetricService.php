<?php


namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\AgentProfile;
use Illuminate\Database\Eloquent\Builder;

class AgencyMetricService
{
    private $startDate = null;
    private $endDate = null;
    private $state = null;
    private $accountManagerId = null;
    private $agencyId = null;
    private $officeId = null;

    public function __construct(array $request)
    {
        $this->startDate = empty($request['start']) ? null : (int) $request['start'];
        $this->endDate = empty($request['end']) ? null : (int) $request['end'];
        $this->state = empty($request['state']) ? null : (int) $request['state'];
        $this->accountManagerId = empty($request['account_manager_id']) ? null : (int) $request['account_manager_id'];
        $this->agencyId = empty($request['agency_id']) ? null : (int) $request['agency_id'];
        $this->officeId = empty($request['office_id']) ? null : (int) $request['office_id'];
    }

    public function getAgencyMetrics()
    {
        return [
            'applications_created' => $this->getTotalApplicationCount(),
            'active_agents' => $this->getActiveAgentCount(),
            'agent_portal' => $this->getAgentPortalCount(),
            'digital_application_platform' => $this->getIgniteApplicationCount(),
            'property_management_system' => $this->getPropertyApplicationCount(),
            'connected_property_me' => $this->getConnectedPropertyMeCount(),
            'connected_our_property' => $this->getConnectedOurPropertyCount(),
        ];
    }

    private function getTotalApplicationCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['hood']]
        );

        return $builder->count();
    }

    private function getActiveAgentCount()
    {
        $builder = AgentProfile::query()
            ->with('user')
            ->whereHas('user', function (Builder $agent) {
                $agent->where('is_active', 1);
            });

        return $builder->count();
    }

    private function getAgentPortalCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [
                ConnectionApplication::SOURCE_MAPPING['hood'],
                ConnectionApplication::SOURCE_MAPPING['foxie']
            ]
        );
        $builder = $this->applyStatusFilter($builder, ConnectionService::STATUS_SUBMITTED);

        return $builder->count();
    }

    private function getIgniteApplicationCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['ignite']]
        );

        return $builder->count();
    }

    private function getPropertyApplicationCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [
                ConnectionApplication::SOURCE_MAPPING['our-property'],
                ConnectionApplication::SOURCE_MAPPING['property_me']
            ]
        );

        return $builder->count();
    }

    private function getConnectedPropertyMeCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['property_me']]
        );
        $builder = $this->applyStatusFilter($builder, ConnectionService::STATUS_ACCEPTED);

        return $builder->count();
    }

    private function getConnectedOurPropertyCount()
    {
        $builder = $this->connectedServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['our-property']]
        );
        $builder = $this->applyStatusFilter($builder, ConnectionService::STATUS_ACCEPTED);

        return $builder->count();
    }

    private function connectedServiceBuilder()
    {
        return ConnectionService::query()
            ->with('connectionApplication.createdBy.user');
    }

    private function applySourceFilter(Builder $builder, array $source): Builder
    {
        return $builder->whereHas('connectionApplication', function (Builder $builder) use ($source) {
            $builder->whereIn('source', $source);
        });
    }

    private function applyStatusFilter(Builder $builder, string $status): Builder
    {
        return $builder->where('status', $status);
    }
}
