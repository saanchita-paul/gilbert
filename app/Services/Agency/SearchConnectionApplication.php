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
    private ?int $tenancyType;
    private $officeId;


    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->createFullTextQueries($request);

        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];
        $this->status = optional($request)['status'];
        $this->leadType = optional($request)['active_lead_type'];
        $this->source = !empty($request['source']) ? (ConnectionApplication::SOURCE_MAPPING[$request['source']] ?? null) : null;
        $this->tenancyType = !empty($request['tenancy_type']) ? ConnectionApplication::TENANCY_MAPPING[$request['tenancy_type']] ?? null: null;
        $this->officeId = !empty($request['office_id']) ? $request['office_id'] : null;



        if(empty($request['sort_by'])) {
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
            ->with('assignedTo')
            ->with('submittedByUser');

        $this->applyFilterLeadType($user)
            ->applyFilterUserOffice($user)
            ->applyFilterSource()
            ->applyFilterOfficeId()
            ->applyFilterForFoxie()
            ->applyFilterTenancyType()
            ->applySearch();

        $this->builder = $this->applySorting($this->builder);

        return $this->builder->paginate($this->perPage);
    }

    /**
     * @return $this
     */
    private function applyFilterLeadType(User $user): static
    {
        if (empty($this->leadType)) {
            $this->builder = $this->builder->where('status', '!=', ConnectionApplication::STATUS_CLOSED);
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
                        ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED)
                        ->whereNull('submitted_by');
            }
        }

        return $this;
    }

    /**
     * @return static
     */
    private function applyFilterSource(): static
    {
        if($this->source) {
            $this->builder = $this->builder->where('source', $this->source);
        }
        return $this;

    }

    /**
     * @return $this
     */
    private function applyFilterOfficeId(): static
    {
        if($this->officeId) {
            $this->builder = $this->builder->where('office_id', $this->officeId);
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function applyFilterTenancyType(): static
    {
        if ($this->tenancyType) {
            $this->builder = $this->builder->where('tenancy_type', $this->tenancyType);
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function applyFilterUserOffice(User $user): static
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
    private function applySearch(): static
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
    private function createFullTextQueries(array $filters): void
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
            $index = 'unit_number,street_number,street_name,city,postcode,state,country,street_address,address_text';
            $this->searchQueries[] = $query->createNew( text:$filters['address'], index: $index);
        }

    }

    /**
     * @return $this
     */
    private function applyFilterForFoxie(): static
    {
        $this->builder = match ($this->source) {
            ConnectionApplication::SOURCE_FOXIE => $this->builder->whereHas('SugerLead' , function(Builder $query){
                $query->whereNull('compare_connect_id')
                    ->orWhere('compare_connect_id', 'N/A');
            }),
            null => $this->builder->where(function (Builder $builder) {
                $builder->doesntHave('SugerLead')
                    ->orWhereHas("SugerLead", fn (Builder $id) => $id->whereNull('compare_connect_id')->orWhere('compare_connect_id', 'N/A'));
            }),
            default => $this->builder
        };

//        $r = $this->builder->pluck('source')->toArray();
//        dd($r);
        return $this;
    }
}
