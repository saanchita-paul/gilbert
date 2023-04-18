<?php

namespace App\Services\Application;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Modules\Reporting\Services\SetDateRage;
use App\Services\FullTextSearch\FullTextQueryInterface;
use App\Services\FullTextSearch\FullTextSearchInterface;
use App\Traits\Agency\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use function optional;
use function resolve;

/**
 *
 */
class SearchConnectionApplication
{

    use Sortable;
    use SetDateRage;

    /**
     * @var mixed|null
     */
    private ?int $perPage;
    /**
     * status
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
    private ?int $triage;
    private ?int $assignee;
    private $officeId;

    private $appId;
    private $movingDate;
    private $agentId;
    private $tenantEmail;
    private ?string $startDate = null;
    private ?string $endDate = null;
    private bool $isDuplicate;

    /**
     * @var string|null
     */
    private $duplication_group_id;

    private ?string $provider = null;

    private ?string $application_service_type = null;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->createFullTextQueries($request);

        $this->perPage = empty($request['per_page']) ? null : (int)$request['per_page'];
        $this->leadType = optional($request)['active_lead_type'];
        $this->source = !empty($request['source']) ? (ConnectionApplication::SOURCE_MAPPING[$request['source']] ?? null) : null;
        $this->tenancyType = !empty($request['tenancy_type']) ? ConnectionApplication::TENANCY_MAPPING[$request['tenancy_type']] ?? null : null;
        $this->triage = !empty($request['triage']) ? ConnectionApplication::TRIAGE_MAPPING[$request['triage']] ?? null : null;
        $this->officeId = !empty($request['office_id']) ? $request['office_id'] : null;
        $this->appId = !empty($request['app_id']) ? $request['app_id'] : null;
        $this->agentId = !empty($request['agent_id']) ? $request['agent_id'] : null;
        $this->tenantEmail = !empty($request['tenant_email']) ? $request['tenant_email'] : null;
        $this->provider = !empty($request['provider_name']) ?  $request['provider_name'] : null;
        $this->isDuplicate = !empty($request['is_duplicate']) ? (bool)$request['is_duplicate'] : false;
        $this->duplication_group_id = !empty($request['duplication_group_id']) ? $request['duplication_group_id'] : null;
        $this->assignee = !empty($request['assignee']) ? $request['assignee'] : null;

        !empty($request['moving_date']) && $this->setDateRangeNoTz($request['moving_date'], $request['moving_date']);

        if (empty($request['sort_by'])) {
            $this->setSortBy('created_at', 'true');
        } else {
            $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
        }

        if ( !empty($request['start_date']) && !empty($request['end_date'])) {
            $this->setDateRange($request['start_date'], $request['end_date']);
        }

        $this->application_service_type = !empty($request['application_service_type']) ? $request['application_service_type'] : null;
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
            ->with('submittedByUser')
            ->with('powershopPaymentInfo')
            ->with('office')
            ->with('authorizedPerson')
            ->with('createdBy')
            ->with('identification')
            ->with('submittedByUser');


        $this->applyFilterLeadType($user)
            ->applyFilterUserOffice($user)
            // ->applyFilterCreatedBy($user)
            ->applyFilterSource()
            ->applyFilterOfficeId()
            ->applyFilterForFoxie()
            ->applyFilterTenancyType()
            ->applyFilterTriage()
            ->applyFilterAppId()
            ->applyFilterMovingDate()
            ->applyFilterAgentId()
            ->applyFilterTenantEmail()
            ->applyFilterByProvider()
            ->applyDuplicateFilter()
            ->applyAssigneeFilter()
            ->applyDateRangeFilter()
            ->applyFilterByService()
            ->applySearch();

        $this->builder = $this->applySorting($this->builder);

        return $this->builder->paginate($this->perPage);
    }

    public function getNBN(User $user): LengthAwarePaginator
    {
        $this->builder = ConnectionApplication::query()
            ->with('connectionServices.reasons')
            ->with('SugerLead')
            ->with('assignedTo')
            ->with('submittedByUser')
            ->with('powershopPaymentInfo')
            ->with('office')
            ->with('authorizedPerson')
            ->with('createdBy')
            ->with('identification')
            ->with('submittedByUser');


        $this
            ->applyFilterLeadType($user)
            ->applyFilterUserOffice($user)
            ->applyFilterSource()
            ->applyFilterAppId()
            ->applyDateRangeFilter()
            ->applyFilterByService()
            ->applySearch();

        $this->builder = $this->applySorting($this->builder);

        return $this->builder->paginate($this->perPage);
    }


    public function getApplicationForAgency(User $user): LengthAwarePaginator
    {
        $this->builder = ConnectionApplication::query()
            ->with('connectionServices.reasons')
            ->with('SugerLead')
            ->with('assignedTo')
            ->with('submittedByUser');

        $this->applyFilterLeadType($user)
            ->applyFilterUserOffice($user)
            ->applyFilterSource()
            // ->applyFilterCreatedBy($user)
            ->applyFilterOfficeId()
            ->applyFilterTenancyType()
            ->applySearch();

        $this->builder = $this->applySorting($this->builder);

        return $this->builder->paginate($this->perPage);
    }

    /**
     * view business docs here: "/docs/business/applications_card_filters.md"
     * @return $this
     */
    private function applyFilterLeadType(User $user): static
    {
        if (!$this->leadType) {
            return $this;
        }

        $statuses = ApplicationStatusFilterMapper::getStatuses($this->leadType);


        if (sizeof($statuses) > 0) {
            $this->builder = $this->builder->whereIn('status', $statuses);
        }

        $this->builder = match ($this->leadType) {
            ConnectionApplication::MY_APPLICATIONS => $this->builder->where('assigned_to', $user->profile->id),
            #note: if any  status needs some special conditions, then add more cases here.
            default => $this->builder
        };

        return $this;
    }

    /**
     * @return static
     */
    private function applyFilterSource(): static
    {
        if (isset($this->source) && gettype($this->source) == 'integer') {
            $this->builder = $this->builder->where('source', $this->source);
        }
        return $this;

    }

    private function applyFilterAppId(): static
    {
        if ($this->appId) {
            $this->builder = $this->builder->where('id', $this->appId);
        }
        return $this;

    }

    private function applyFilterMovingDate(): static
    {
        if ($this->startDate && $this->endDate) {
            $this->builder = $this->builder
                ->where('moving_date', '>=', $this->startDate)
                ->where('moving_date', '<=', $this->endDate);
        }
        return $this;
    }

    private function applyFilterTenantEmail(): static
    {
        if ($this->tenantEmail) {
            $this->builder = $this->builder
                ->where('email', 'like', "%$this->tenantEmail%");
        }
        return $this;
    }

    private function applyFilterAgentId(): static
    {
        if ($this->agentId) {
            $this->builder = $this->builder->where('created_by', $this->agentId);
        }
        return $this;

    }

    /**
     * @return $this
     */
    private function applyFilterOfficeId(): static
    {
        if ($this->officeId) {
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
    private function applyFilterTriage(): static
    {
        if ($this->triage) {
            $this->builder = $this->builder->where('is_triage', $this->triage);
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
    private function applyFilterCreatedBy(User $user): static
    {
        if ($user->profile_type === AgentProfile::class) {
            $this->builder = $this->builder
                ->where('created_by', $user->profile->id);
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
            $this->searchQueries[] = $query->createNew(text: $filters['tenant_name'], index: 'first_name, middle_name, last_name');
        }
        if (!empty($filters['phone'])) {
            $this->searchQueries[] = $query->createNew(text: $filters['phone'], index: 'phone,homephone');
        }
        if (!empty($filters['address'])) {
            $index = 'unit_number,street_number,street_name_only,city,postcode,state,country,street_address,address_text';
            $this->searchQueries[] = $query->createNew(text: $filters['address'], index: $index);
        }
    }

    /**
     * @return $this
     */
    private function applyFilterForFoxie(): static
    {
        $this->builder = match ($this->source) {
            ConnectionApplication::SOURCE_FOXIE => $this->builder->whereHas('SugerLead', function (Builder $query) {
                $query->whereNull('compare_connect_id')
                    ->orWhere('compare_connect_id', 'N/A');
            }),
            null => $this->builder->where(function (Builder $builder) {
                $builder->doesntHave('SugerLead')
                    ->orWhereHas("SugerLead", fn(Builder $id) => $id->whereNull('compare_connect_id')->orWhere('compare_connect_id', 'N/A'));
            }),
            default => $this->builder
        };
        return $this;
    }

    /**
     * @return $this
     */
    private function applyFilterByProvider(): static
    {
        if ($this->provider) {
            $providerList = $this->mapProviderList($this->provider);
            $this->builder = $this->builder->whereHas('connectionServices', function (Builder $query) use ($providerList) {
                $query->whereIn('provider_name', $providerList);
            });
        }
        return $this;
    }

    private function mapProviderList($providers){
        return explode(",", $providers);
    }

    private function applyDuplicateFilter(): static
    {
        if ($this->isDuplicate) {
            $this->builder = $this->builder
                ->where('is_duplicate', true);
        }

        if (!empty($this->duplication_group_id)) {
            $this->builder = $this->builder
                ->where('duplication_group_id', $this->duplication_group_id);
        }
        return $this;
    }

    private function applyDateRangeFilter(): static
    {
        if ($this->startDate && $this->endDate) {

            $this->builder = $this->builder
                ->where('created_at', '>=', $this->dateStart)
                ->where('created_at', '<=', $this->dateEnd);
        }
        return $this;
    }

    /**
     * Apply assignee filter
     *
     * @return $this
     */
    private function applyAssigneeFilter(): static
    {
        if ($this->assignee) {
            $this->builder = $this->builder->where('assigned_to', $this->assignee);
        }
        return $this;
    }

    private function applyFilterByService(): static {
        if ($this->application_service_type) {
            $this->builder = $this->builder->whereHas('connectionServices', function (Builder $query){
                $query->whereIn('service_type', ['power', 'gas']);
            });
        }
        return $this;

    }
}
