<?php

namespace App\Services\Agency;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchAgentProfileService
{
    use Sortable, Searchable;

    /**
     * @var mixed|null
     */
    private ?int $perPage;

    private ?int $officeId;


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];

        $this->setSearch(optional($request)['search']);
        $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
    }

    /**
     * Getting Offices list
     *
     * @param int|null $officeId
     * @return LengthAwarePaginator
     */
    public function get(int $officeId = null): LengthAwarePaginator
    {
        $agencyBuilder = $this->createAgencyBuilder();

        if ($officeId) {
            $agencyBuilder->where('office_id', $officeId);
        }

        $agencyBuilder = $this->applySearch($agencyBuilder, ['first_name', 'last_name']);
        $agencyBuilder = $this->applySorting($agencyBuilder);

        return $agencyBuilder->paginate($this->perPage);
    }

    /**
     * Creating agency builder
     *
     */
    private function createAgencyBuilder()
    {
        return AgentProfile::query()
            ->with('user.roles:name')
            ->withCount('createdApplications as application_count')
            ->withMax('createdApplications as last_created', 'created_at');
    }

    public function getConversionCount(int $officeId): int
    {
        $this->officeId = $officeId;

        $totalCreatedApplication = ConnectionApplication::query()
            ->where('created_by', '=', $this->officeId)
            ->whereHas('connectionServices', function ($query) {
                $query->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS]);
            })
            ->count();

        $totalSubmittedApplication = ConnectionApplication::query()
            ->where('created_by', '=', $this->officeId)
            ->whereHas('connectionServices', function ($query) {
                $query->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS]);
                $query->whereIn('status', [
                    ConnectionService::STATUS_SUBMITTED,
                    ConnectionService::STATUS_ENERGY_SUBMIT,
                    ConnectionService::STATUS_ACCEPTED,
                    ConnectionService::STATUS_REJECTED,
                    ConnectionService::AC_MANUAL_PROCESSING
                ]);
            })
            ->whereNotIn('status', [ConnectionApplication::STATUS_CLOSED])
            ->count();

        return $totalCreatedApplication !== 0 ? number_format((($totalSubmittedApplication / $totalCreatedApplication) * 100), 0) : 0;
    }

    private function createAgentBuilder(): Builder
    {
        return AgentProfile::query();
    }

    public function getAgentList(int $officeId = null): LengthAwarePaginator
    {
        $agencyBuilder = $this->createAgentBuilder();

        if ($officeId) {
            $agencyBuilder->where('office_id', $officeId);
        }

        $agencyBuilder = $this->applySearch($agencyBuilder, ['first_name', 'last_name']);
        $agencyBuilder = $this->applySorting($agencyBuilder);

        return  $agencyBuilder->paginate($this->perPage);
    }
}

