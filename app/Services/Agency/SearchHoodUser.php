<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\HoodProfile;
use App\Models\Office;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchHoodUser
{
    use Sortable, Searchable;

    /**
     * @var mixed|null
     */
    private ?int $perPage;
    private ?array $roles = [];


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];

        $this->roles = empty($request['roles']) ? [] : $request['roles'];

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
        $builder = $this->createAgencyBuilder();


        $builder = $this->applySearch($builder, ['first_name', 'last_name']);
        $builder = $this->applySorting($builder);
        $builder = $this->applyRolesFilter($builder);

        return  $builder->paginate($this->perPage);
    }

    /**
     * Creating agency builder
     *
     * @return Builder
     */
    private function createAgencyBuilder(): Builder
    {
        return HoodProfile::query()
            ->with('user.roles:name');
    }

    private function applyRolesFilter(Builder $builder): Builder
    {
        if (sizeof($this->roles) > 0) {
            return $builder->whereHas('user.roles', function (Builder $roles) {
                $roles->whereIn('name', $this->roles);
            });
        }
        return  $builder;
    }
}
