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

    private array $submisssionType = [
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::STATUS_CANT_CONNECT,
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $conversionsType = [ConnectionService::STATUS_ACCEPTED];
    private array $closedType = [ConnectionApplication::STATUS_CLOSED];
    private array $rejectedType = [ConnectionService::STATUS_REJECTED];

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

    public function totalSubmissions()
    {

        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->submisssionType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->where('submitted_at', '>=', $this->startDate)
            ->where('submitted_at', '<=', $this->endDate)
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
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function totalDeclined()
    {
        return [
            'total' => 0
        ];
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

    private function getTotalOpenApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', $this->openType);
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getTotalClosedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', [$this->closedType])
                        ->where('closed_at', '>=', $this->startDate)
                        ->where('closed_at', '<=', $this->endDate);
                })
                ->count();
    }

    // #[ArrayShape(['submission' => "array", 'conversions' => "array", 'rejected' => "array", 'declined' => "array"])]
    public function getEnergyReport(): array
    {
        return [
            'submission' => $this->mapperService->setEnergyData($this->totalSubmissions())->getReportData(),
            'conversions' => $this->mapperService->setEnergyData($this->totalConversions())->getReportData(),
            'rejected' => $this->mapperService->setEnergyData($this->totalRejected())->getReportData(),
            'declined' => $this->totalDeclined(),
            'waiting_for_connection' => $this->mapperService->setEnergyData($this->totalWaitingForConnection())->getReportData(),
            "total_open_application" => $this->getTotalOpenApplication(),
            "total_consent_pending" => 0,
            "total_closed" => $this->getTotalClosedApplication(),
        ];
    }
}
