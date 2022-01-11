<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Office;
use App\Models\ConnectionService;
use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SearchAgencyService
{
    use Searchable, Sortable;
    /**
     * @var mixed|null
     */
    private ?int $perPage;


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];
        $this->setSearch(optional($request)['search']);
        $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get(): LengthAwarePaginator
    {
        $agencyBuilder = Agency::query()
            ->withCount('offices')
            ->withCount('applications')
            ->withMax('applications as last_application', 'created_at');

        $agencyBuilder = $this->applySearch($agencyBuilder, ['name']);

        $agencyBuilder = $this->applySorting($agencyBuilder);

        return  $agencyBuilder->paginate($this->perPage);
    }

    public function getConversionCount(int $agencyId): int
    {
        $totalConnected = ConnectionService::query()
            ->whereHas('connectionApplication', function ($query) use ($agencyId) {
                $query->where('agency_id', '=', $agencyId);
            })
            ->whereIn('status', [ConnectionService::STATUS_ACCEPTED])
            ->count();

        $totalSubmitted = ConnectionService::query()
            ->whereHas('connectionApplication', function ($query) use ($agencyId) {
                $query->where('agency_id', '=', $agencyId);
            })
            ->whereIn('status', [
                ConnectionService::STATUS_ACCEPTED,
                ConnectionService::STATUS_REJECTED,
                ConnectionService::STATUS_ENERGY_SUBMIT,
                ConnectionService::STATUS_CLOSED,
                ConnectionService::STATUS_CANT_CONNECT,
                ConnectionService::AC_MANUAL_PROCESSING
            ])
            ->count();
       
        return $totalSubmitted !== 0 ? number_format((($totalConnected / $totalSubmitted) * 100), 0) : 0;
    }

    public function getActiveUserCount(int $agencyId): int
    {
        $agentProfiles = AgentProfile::where('agency_id', $agencyId)->pluck('id');
        return User::whereIn('profile_id', $agentProfiles)
            ->where('profile_type', 'App\\Models\\AgentProfile')
            ->where('is_active', 1)
            ->count();
    }

    public function getRentRollCount(int $agencyId): int
    {
        return Office::where('agency_id', $agencyId)->sum('rent_roll');
    }
}
