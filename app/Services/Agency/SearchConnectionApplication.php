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
    private mixed $source;


    public function __construct(array $request)
    {
        $this->perPage = empty($request['per_page']) ? null : (int)$request['per_page'];
        $this->status = optional($request)['status'];
        $this->leadType = optional($request)['active_lead_type'];
        $this->source = ConnectionApplication::SOURCE_MAPPING[$request['source']??''] ??  ConnectionApplication::SOURCE_ALL;
        $this->setSearch(optional($request)['search']);

        if(empty(optional($request)['sort_by'])) {
            $this->setSortBy('created_at', 'true');
        } else {
            $this->setSortBy(optional($request)['sort_by'], optional($request)['is_descending']);
        }

    }

    /**
     * @param $user
     * @return LengthAwarePaginator
     */
    public function get($user): LengthAwarePaginator
    {
        $builder = ConnectionApplication::query()
            ->with('connectionServices')
            ->with('assignedTo')
            ->with('SugerLead');

        if($this->source !== ConnectionApplication::SOURCE_ALL) {
            $builder->where('source', $this->source);
            $builder = $this->filterLeadForFoxie($builder);
        }



        if ($this->leadType) {
            if ($this->leadType === 'submitted') {
                $builder = $this->leadType !== ConnectionApplication::MY_APPLICATIONS
                    ? $builder->whereIn('status', [4, 5, 6, 7])
                    : $builder->where('assigned_to', $user->profile->id)
                              ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED);
            } else {
                $builder = $this->leadType !== ConnectionApplication::MY_APPLICATIONS
                    ? $builder->where('status', ConnectionApplication::STATUS_MAPPING[$this->leadType])
                    : $builder->where('assigned_to', $user->profile->id)
                              ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED);
            }

        }

        if ($user->profile_type === AgentProfile::class) {
            $builder->where('office_id', $user->profile->office_id);
            $builder->where('agency_id', $user->profile->agency_id);
        }

        $builder = $this->applySearch($builder, ['first_name', 'last_name']);

        $builder = $this->applySorting($builder);


        return $builder->paginate($this->perPage);
    }

    private function filterLeadForFoxie($builder){
        if($this->source == ConnectionApplication::SOURCE_FOXIE){
            $builder =  $builder->whereHas('SugerLead' , function($query){
                $query->where('compare_connect_id' , null)
                      ->orWhere('compare_connect_id', 'N/A')
                      ->orWhere('compare_connect_id', 'n/a')
                      ->orWhere('compare_connect_id', 'N/a')
                      ->orWhere('compare_connect_id', 'n/A');
            });
        }
        return $builder;
    }
}
