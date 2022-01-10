<?php


namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\AgentProfile;
use Illuminate\Database\Eloquent\Builder;
use App\Services\AddressMapperService;
use Carbon\Carbon;

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
        $this->state = empty($request['state']) ? null : $request['state'];
        $this->accountManagerId = empty($request['account_manager_id']) ? null : $request['account_manager_id'];
        $this->agencyId = empty($request['agency_id']) ? null : $request['agency_id'];
        $this->officeId = empty($request['office_id']) ? null : $request['office_id'];

        !empty($request['start']) && !empty($request['end']) &&
        $this->setDateRangeNoTz($request['start'], $request['end']);
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
        $builder = $this->connectionServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['hood']]
        );
        
        return $builder->count();
    }

    private function getAgentPortalCount()
    {
        $builder = $this->connectionServiceBuilder();
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
        $builder = $this->connectionServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['ignite']]
        );

        return $builder->count();
    }

    private function getPropertyApplicationCount()
    {
        $builder = $this->connectionServiceBuilder();
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
        $builder = $this->connectionServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['property_me']]
        );
        $builder = $this->applyStatusFilter($builder, ConnectionService::STATUS_ACCEPTED);

        return $builder->count();
    }

    private function getConnectedOurPropertyCount()
    {
        $builder = $this->connectionServiceBuilder();
        $builder = $this->applySourceFilter(
            $builder,
            [ConnectionApplication::SOURCE_MAPPING['our-property']]
        );
        $builder = $this->applyStatusFilter($builder, ConnectionService::STATUS_ACCEPTED);

        return $builder->count();
    }

    private function getActiveAgentCount()
    {
        $builder = AgentProfile::query()
            ->with('user')
            ->with('office')
            ->whereHas('user', function (Builder $agent) {
                $agent->where('is_active', 1);
            });

        if($this->startDate && $this->endDate) {
            $builder = $builder
                ->where('created_at', '>=' , $this->startDate)
                ->where('created_at', '<=' , $this->endDate);
        }

        if($this->accountManagerId) {
            $builder = $builder->whereHas('office', function (Builder $builder) {
                $builder = $builder->where('hood_agent_id', $this->accountManagerId);
            });
        }

        $this->officeId && $builder = $builder->where('office_id', $this->officeId);
        $this->agencyId && $builder = $builder->where('agency_id', $this->agencyId);

        return $builder->count();
    }

    private function connectionServiceBuilder()
    {
        $builder = ConnectionService::query()
            ->with('connectionApplication.office');

        $builder = $this->applyDateFilter($builder, 'created_at');
        $builder = $this->applyStateFilter($builder, 'state');
        $builder = $this->applyAccManagerFilter($builder, 'hood_agent_id');
        $builder = $this->applyOfficeFilter($builder, 'office_id');
        $builder = $this->applyAgencyFilter($builder, 'agency_id');

        return $builder;
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

    private function applyDateFilter(Builder $builder, $column): Builder
    {
        if($this->startDate && $this->endDate) {
            $builder->whereHas('connectionApplication', function (Builder $builder) use ($column) {
                $builder = $builder
                    ->where($column, '>=' , $this->startDate)
                    ->where($column, '<=' , $this->endDate);
            });
        }
        return $builder;
    }

    private function applyStateFilter(Builder $builder, $column): Builder
    {
        if($this->state) {
            $addressService = new AddressMapperService();
            $state = $addressService->mapState($this->state);

            $builder->whereHas('connectionApplication', function (Builder $builder) use ($column, $state) {
                $builder = $builder->where($column, $state);
            });
        }
        return $builder;
    }

    private function applyAgencyFilter(Builder $builder, $column): Builder
    {
        if($this->agencyId) {
            $builder->whereHas('connectionApplication', function (Builder $builder) use ($column) {
                $builder = $builder->where($column, $this->agencyId);
            });
        }
        return $builder;
    }

    private function applyOfficeFilter(Builder $builder, $column): Builder
    {
        if($this->officeId) {
            $builder->whereHas('connectionApplication', function (Builder $builder) use ($column) {
                $builder = $builder->where($column, $this->officeId);
            });
        }
        return $builder;
    }

    private function applyAccManagerFilter(Builder $builder, $column): Builder
    {
        if($this->accountManagerId) {
            $builder->whereHas('connectionApplication.office', function (Builder $builder) use ($column) {
                $builder = $builder->where($column, $this->accountManagerId);
            });
        }
        return $builder;
    }

    private function setDateRangeNoTz(string $start, string $end)
    {
        $this->startDate = Carbon::parse($start)->toDateTimeString();
        $this->endDate = Carbon::parse($end)
            ->addHours(23)
            ->addMinutes(59)
            ->addSeconds(59)
            ->toDateTimeString();
    }
}
