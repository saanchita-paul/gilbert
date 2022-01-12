<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\User;
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
    private $result = [];

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
        $builder = DB::table('connection_applications');
        if ($this->officeId) {
            $builder->where('office_id', $this->officeId);
        }

        $this->result = $builder->select('status', DB::raw("count(*) as total"))
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
            ->where('status' , '!=' , ConnectionApplication::STATUS_CLOSED)
            ->select(DB::raw("count(*) as count"))
            ->get()->toArray();

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
        foreach ($this->result as $datum) {
            $status = array_search($datum->status, ConnectionApplication::STATUS_MAPPING);
            if ($status) {
                $va = array_search($status, array_column($this->metrics, 'type'));
                $this->metrics[$va]['count'] += $datum->total;

                if($status === 'processing' || $status === 'accepted' || $status === 'rejected')
                {
                    $this->metrics[3]['count'] += $datum->total;
                }
            }
        }
    }
}
