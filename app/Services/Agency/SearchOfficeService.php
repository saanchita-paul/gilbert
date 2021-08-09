<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\Office;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchOfficeService
{
    /**
     * @var mixed|null
     */
    private ?string $search;

    /**
     * @var mixed|null
     */
    private ?int $perPage;

    /**
     * @var string|null
     */
    private ?string $sortBy;

    /**
     * @var string|null
     */
    private ?string $sortDir;


    public function __construct(array $request)
    {
        $this->search = empty($request['search']) ? null : $request['search'];
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];
        $this->sortBy = empty($request['sort_by']) ? null : $request['sort_by'];
        $this->sortDir = optional($request)['is_descending'] === 'true' ? 'desc' : 'asc';
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get(): LengthAwarePaginator
    {
        $agencyBuilder = Office::query()
            ->with('agency')
            ->withCount('agents')
            ->withCount('applications');

        $agencyBuilder = $this->applySearch($agencyBuilder);

        $agencyBuilder = $this->applySorting($agencyBuilder);


        return  $agencyBuilder->paginate($this->perPage);
    }

    /**
     * applying sorting
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    private function applySorting(Builder $builder): Builder
    {
        if ($this->sortBy) {
            return $builder->orderBy($this->sortBy, $this->sortDir);
        }
        return $builder;
    }

    /**
     * applying search
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    //todo: replace this 'like' search with FULL-TEXT-SEARCH
    private function applySearch(Builder $builder): Builder
    {
        if (!$this->search) {
            return $builder;
        }
        $keys = explode(' ', $this->search);
        return $builder->where(function (Builder $builder) use ($keys) {
            foreach ($keys as $key) {
                $builder->orWhere('name', 'like', '%' . $key . '%');
            }
        });
    }

}
