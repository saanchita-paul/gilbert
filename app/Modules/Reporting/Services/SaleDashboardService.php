<?php

namespace Reporting\Services;

use App\Models\ConnectionService;
use Carbon\Carbon;
use DB;

class SaleDashboardService
{
    private string $start;
    private string $end;

    public function __construct(string $start, string $end)
    {
        $this->start = Carbon::parse($start);
        $this->end = $end;
    }

    public function run()
    {
        $res = ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', [ConnectionService::STATUS_SUBMITTED, ConnectionService::STATUS_EA_SUBMIT])
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
        return (new MapEnergyReport($res))->getReportData();
    }
}
