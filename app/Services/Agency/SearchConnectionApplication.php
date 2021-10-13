<?php

namespace App\Services\Agency;

use App\Models\AgentProfile;
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
    private ?string $leadType;


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int) $request['per_page'];
        $this->status = optional($request)['status'];
        $this->leadType = optional($request)['active_lead_type'];

        $this->setSearch(optional($request)['search']);
        $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
    }

    /**
     * @param $user
     * @return LengthAwarePaginator
     */
    public function get($user): LengthAwarePaginator
    {
        $builder = ConnectionApplication::query()
            ->with('connectionServices')
            ->with('assignedTo');

        info('info', [$this->leadType]);

        if($this->leadType) {
            $builder = $this->leadType !== ConnectionApplication::MY_APPLICATIONS
                ? $builder->where('status', ConnectionApplication::STATUS_MAPPING[$this->leadType])
                :  $builder->where('assigned_to', $user->profile->id);

        }

        if ($user->profile_type === AgentProfile::class) {
            $builder->where('office_id', $user->profile->office_id);
            $builder->where('agency_id', $user->profile->agency_id);
        }

        $builder = $this->applySearch($builder, ['first_name', 'last_name']);

        $builder = $this->applySorting($builder);


        return  $builder->paginate($this->perPage);
    }
}
