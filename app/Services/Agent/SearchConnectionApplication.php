<?php

namespace App\Services\Agent;

use App\Models\ConnectionApplication;
use App\Models\User;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchConnectionApplication
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
    public function get($user): LengthAwarePaginator
    {
        $agencyBuilder = ConnectionApplication::query()
            ->where('office_id', $user->profile->office_id)
            ->with('connectionServices');

        $agencyBuilder = $this->applySearch($agencyBuilder, ['first_name', 'last_name']);

        $agencyBuilder = $this->applySorting($agencyBuilder);


        return  $agencyBuilder->paginate($this->perPage);
    }
}
