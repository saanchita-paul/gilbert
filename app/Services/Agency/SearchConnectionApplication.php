<?php

namespace App\Services\Agency;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Services\FullTextSearch\FullTextQuery;
use App\Services\FullTextSearch\FullTextQueryInterface;
use App\Services\FullTextSearch\FullTextSearchInterface;
use App\Traits\Agency\Searchable;
use App\Traits\Agency\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\NoReturn;

/**
 *
 */
class SearchConnectionApplication
{

    use Sortable;

    /**
     * @var mixed|null
     */
    private ?int $perPage;
    /**
     * @var string|mixed|null
     */
    private ?string $status;
    /**
     * @var string|mixed|null
     */
    private ?string $leadType;
    /**
     * @var mixed|int
     */
    private mixed $source;
    /**
     * @var FullTextQueryInterface[]
     */
    private array $searchQueries = [];

    /**
     * @var Builder
     */
    private Builder $builder;


    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->createFullTextQueries($request);

        $this->perPage = empty($request['per_page']) ? null : (int)$request['per_page'];
        $this->status = optional($request)['status'];
        $this->leadType = optional($request)['active_lead_type'];
        $this->source = ConnectionApplication::SOURCE_MAPPING[$request['source']??''] ??  ConnectionApplication::SOURCE_ALL;

        if(empty(optional($request)['sort_by'])) {
            $this->setSortBy('created_at', 'true');
        } else {
            $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
        }

    }

    /**
     * @param User $user
     * @return LengthAwarePaginator
     */
    public function get(User $user): LengthAwarePaginator
    {
        $this->builder = ConnectionApplication::query()
            ->with('connectionServices.reasons')
            ->with('SugerLead')
            ->with('assignedTo');

        $this->filterByLeadType($user)
            ->filterByOffice($user)
            ->filterLeadForFoxie()
            ->applySearch();

        $this->builder = $this->applySorting($this->builder);

        return $this->builder->paginate($this->perPage);
    }

    /**
     * @return $this
     */
    private function filterByLeadType(User $user): static
    {
        if (empty($this->leadType)) {
            $this->builder = ConnectionApplication::query()
                ->where('status', '!=', ConnectionApplication::STATUS_CLOSED)
                ->with('connectionServices')
                ->with('assignedTo');
        }


        //todo: need to refactor this "BAD" code (ask Sazzad if needed).
        if ($this->leadType) {
            if ($this->leadType === 'submitted') {
                $this->builder = $this->leadType !== ConnectionApplication::MY_APPLICATIONS
                    ? $this->builder->whereIn('status', [4, 5, 6, 7])
                    : $this->builder->where('assigned_to', $user->profile->id)
                        ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED);
            } else {
                $this->builder = $this->leadType !== ConnectionApplication::MY_APPLICATIONS
                    ? $this->builder->where('status', ConnectionApplication::STATUS_MAPPING[$this->leadType])
                    : $this->builder->where('assigned_to', $user->profile->id)
                        ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED);
            }
        }

        return $this;
    }

    /**
     * @return void
     */
    public function applyOthersFilter()
    {
        if($this->source !== ConnectionApplication::SOURCE_ALL) {
            $this->builder->where('source', $this->source);
        }
    }

    /**
     * @return $this
     */
    private function filterByOffice(User $user): static
    {
        if ($user->profile_type === AgentProfile::class) {
            $this->builder = $this->builder
                ->where('office_id', $user->profile->office_id)
                ->where('agency_id', $user->profile->agency_id);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function applySearch(): static
    {
        /** @var FullTextSearchInterface $fts */
        $fts = resolve(FullTextSearchInterface::class);
        $this->builder = $fts->applyAndSearches($this->builder, queries: $this->searchQueries);

        return $this;
    }
    /**
     * building search query
     *
     * @param array $filters
     *
     * @return void
     */
    #[NoReturn]
    public function createFullTextQueries(array $filters): void
    {
        /** @var FullTextQueryInterface $query */
        $query = resolve(FullTextQueryInterface::class);

        if (!empty($filters['tenant_name'])) {
            $this->searchQueries[] = $query->createNew( text:$filters['tenant_name'], index: 'first_name, middle_name, last_name');
        }
        if (!empty($filters['phone'])) {
            $this->searchQueries[] = $query->createNew( text:$filters['phone'], index: 'phone,homephone');
        }
        if (!empty($filters['address'])) {
            $index = 'unit_number,street_number,street_name,city,postcode,state,country';
            $this->searchQueries[] = $query->createNew( text:$filters['address'], index: $index);
        }

    }

    /**
     * @return $this
     */
    private function filterLeadForFoxie(): static
    {
        $this->builder = match ($this->source) {
            ConnectionApplication::SOURCE_FOXIE => $this->builder->whereHas('SugerLead' , function($query){
                $query->where('compare_connect_id' , null)
                    ->orWhere('compare_connect_id', 'N/A');
            }),
            ConnectionApplication::SOURCE_ALL => $this->builder->where(function (Builder $builder) {
                $builder->doesntHave('SugerLead')
                    ->orWhereHas("SugerLead", fn (Builder $id) => $id->whereNull('compare_connect_id')->orWhere('compare_connect_id', 'N/A'));
            }),
            default => $this->builder
        };

        return $this;
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
}
