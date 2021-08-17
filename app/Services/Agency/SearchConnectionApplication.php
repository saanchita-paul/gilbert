<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
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
    private array $statusMap = [
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
        $agencyBuilder = ConnectionApplication::query()
            ->where('office_id', $user->profile->office_id)
            ->with('connectionServices')
            ->with('assignedTo');

        $agencyBuilder = $this->applySearch($agencyBuilder, ['first_name', 'last_name']);

        $agencyBuilder = $this->applySorting($agencyBuilder);


        return  $agencyBuilder->paginate($this->perPage);
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

        $statusValue = ConnectionApplication::STATUS_MAPPING[$this->status];

        return $builder->where('status', $statusValue);
    }
}
