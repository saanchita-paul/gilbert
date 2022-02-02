<?php

namespace App\Services\Applications;

use App\Models\ConnectionApplication;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ApplicationsMetricsService
{
    private $metrics = [
        [
            "type" => "assigned",
            "count" => 0
        ],
        [
            "type" => "unassigned",
            "count" => 0
        ],
        [
            "type" => "escalated",
            "count" => 0
        ],
        [
            "type" => "submitted",
            "count" => 0
        ],
        [
            "type" => "closed",
            "count" => 0
        ]
    ];
    /**
     * @var array
     */
    private $results = [];

    public function __construct(public int $assignedUserId, public ?int $officeId = null) {
        $this->fetchOthers()
            ->fetchMine()
            ->calculate();
    }

    public function toArray(): array
    {
        return $this->metrics;
    }

    /**
     * Getting Applications analytics
     *
     * @return static
     */
    private function fetchOthers(): static
    {
        $builder = DB::table('connection_applications', 'ca');
        if ($this->officeId) {
            $builder->where('office_id', $this->officeId);
        }

        $this->results = $builder->select('status', DB::raw("count(*) as total"))
            ->leftJoin("suger_leads as fx", 'ca.id', '=', 'fx.connection_application_id')
            ->where(function (Builder $builder) {
                $builder->whereNull('fx.compare_connect_id')->orWhere('fx.compare_connect_id', 'N/A');
            })
            ->groupBy('status')
            ->get();
        return  $this;
    }

    /**
     * @return ApplicationsMetricsService
     */
    private function fetchMine(): static
    {
        $res = DB::table('connection_applications')
            ->where('assigned_to', $this->assignedUserId)
            ->where('status', ConnectionApplication::STATUS_ASSIGNED)
            ->select(DB::raw("count(*) as count"))
            ->get()
            ->toArray();

        $this->metrics[] = array_merge(['type' => 'my_application'], (array)array_shift($res));
        return $this;
    }

    /**
     * Mapping status value
     *
     * @return void
     */
    private function calculate(): void
    {
        foreach ($this->results as $result) {
            $status = ApplicationStatusFilterMapper::getFilter($result->status);
            if ($status) {
                $va = array_search($status, array_column($this->metrics, 'type'));
                $this->metrics[$va]['count'] += $result->total;
            }
        }
    }


}
