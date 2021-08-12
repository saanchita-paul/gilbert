<?php

namespace App\Services\Agent;

use App\Models\ConnectionApplication;
use App\Models\User;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchConnectionApplication
{

    use Searchable, Sortable;
    /**
     * @var mixed|null
     */
    private ?int $perPage;
    private ?string $status;
    private $statusMap = [
        'unassigned' => 1,
        'assigned' => 2,
        'escalated' => 3,
        'submitted' => 4,
    ];


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];
        $this->status = optional($request)['status'];
        $this->setSearch(optional($request)['search']);
        $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get($user): LengthAwarePaginator
    {
        $applicationBuilder = ConnectionApplication::query()
            ->where('office_id', $user->profile->office_id)
            ->with('connectionServices');

        $applicationBuilder = $this->applyFilter($applicationBuilder);

        $applicationBuilder = $this->applySearch($applicationBuilder, 'first_name', 'last_name');

        $applicationBuilder = $this->applySorting($applicationBuilder);


        return  $applicationBuilder->paginate($this->perPage);
    }

    /**
     * Apply filters
     *
     * @return Builder
     */
    private function applyFilter(Builder $builder): Builder
    {
        if (!$this->status || !array_key_exists($this->status, $this->statusMap)) {
            return $builder;
        }

        $statusValue = $this->statusMap[$this->status];

        return $builder->where('status', $statusValue);
    }
}
