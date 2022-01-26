<?php

namespace Reporting\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Modules\Reporting\Services\SetDateRage;
use Carbon\Carbon;
use DB;
use Doctrine\DBAL\Driver\IBMDB2\Connection;
use JetBrains\PhpStorm\ArrayShape;

class EnergyReport
{
    use SetDateRage;
    private string $startDate;
    private string $endDate;

    private $timezone;

    private array $submissionType = [
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_CANT_CONNECT,
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $conversionsType = [ConnectionService::STATUS_ACCEPTED];
    private array $closedType = [ConnectionApplication::STATUS_CLOSED];
    private array $rejectedType = [
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_CANT_CONNECT
    ];

    private array $waitingConnectionType = [
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $openType = [
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    /**
     * @var MapEnergyReport
     */
    private MapEnergyReport $mapperService;

    public function __construct(string $startDate, string $endDate)
    {
        $this->setDateRange($startDate, $endDate);

        $this->mapperService = new MapEnergyReport();
    }

    private function getTotalNewApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication')
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getUnassignedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereNull('assigned_to');
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getAssignedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereNotNull('assigned_to');
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getTotalClosedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', [$this->closedType]);
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    } 
    
    public function totalSubmissions()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->submissionType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalWaitingForConnection()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->waitingConnectionType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalConnected()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->conversionsType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
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
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalDeclined()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->rejectedType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
            ->whereHas('reasons', function ($query) {
                $query->whereRaw('LOWER(reason_code) in (?)', ['credit_check']);
            })
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    // #[ArrayShape(['submission' => "array", 'conversions' => "array", 'rejected' => "array", 'declined' => "array"])]
    public function getEnergyReport(): array
    {
        return [
            "total_new_application" => $this->getTotalNewApplication(),
            "unassigned_application" => $this->getUnassignedApplication(),
            "assigned_application" => $this->getAssignedApplication(),
            "total_consent_pending" => 0,
            "total_closed" => $this->getTotalClosedApplication(),
            'successful_submission' => $this->mapperService->setEnergyData($this->totalSubmissions())->getReportData(),
            'waiting_for_connection' => $this->mapperService->setEnergyData($this->totalWaitingForConnection())->getReportData(),
            'connected' => $this->mapperService->setEnergyData($this->totalConnected())->getReportData(),
            'rejected' => $this->mapperService->setEnergyData($this->totalRejected())->getReportData(),
            'declined' => $this->mapperService->setEnergyData($this->totalDeclined())->getReportData(), 
        ];
    }
}
