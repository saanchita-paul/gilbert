<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Pagination\LengthAwarePaginator;

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
            ->withCount('applications');

        $agencyBuilder = $this->applySearch($agencyBuilder, 'name');

        $agencyBuilder = $this->applySorting($agencyBuilder);


        return  $agencyBuilder->paginate($this->perPage);
    }
}
