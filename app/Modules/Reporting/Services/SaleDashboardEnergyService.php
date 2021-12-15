<?php

namespace Reporting\Services;

use App\Models\ConnectionService;
use Carbon\Carbon;
use DB;

class SaleDashboardEnergyService
{
    private string $startDate;
    private string $endDate;

    private array $submisssionType = [ConnectionService::STATUS_SUBMITTED , ConnectionService::STATUS_EA_SUBMIT];
    private array $conversionsType = [ConnectionService::STATUS_ACCEPTED];
    private array $rejectedType    = [ConnectionService::STATUS_REJECTED];

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate   = Carbon::parse($endDate)->addHours(11)->addMinutes(59)->addSeconds(59);
    }

    public function totalSubmissions()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->submisssionType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at' , '>=' , $this->startDate)
            ->where('submitted_at' , '<=' , $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalConversions()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->conversionsType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at' , '>=' , $this->startDate)
            ->where('submitted_at' , '<=' , $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalRejected()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->rejectedType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at' , '>=' , $this->startDate)
            ->where('submitted_at' , '<=' , $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalDeclined()
    {
        return [
            'total' => 100
        ];
    }

    public function getEnergyReport(){
        return [
            'submission'  => $this->totalSubmissions(),
            'conversions' => $this->totalConversions(),
            'rejected'    => $this->totalRejected(),
            'declined'    => $this->totalDeclined(),
        ];
    }
}
