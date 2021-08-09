<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\Office;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchOfficeService
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
     * @return LengthAwarePaginator
     */
    public function get(): LengthAwarePaginator
    {
        $agencyBuilder = Office::query()
            ->with('agency')
            ->withCount('agents')
            ->withCount('applications');

        $agencyBuilder = $this->applySearch($agencyBuilder, 'name');
        $agencyBuilder = $this->applySorting($agencyBuilder);

        return  $agencyBuilder->paginate($this->perPage);
    }
}
