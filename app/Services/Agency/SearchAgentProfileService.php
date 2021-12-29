<?php

namespace App\Services\Agency;

use App\Models\AgentProfile;
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
     * @return Builder
     */
    private function createAgencyBuilder(): Builder
    {
        return AgentProfile::query()
            ->with('user.roles:name')
            ->with('createdApplications');
    }
}
