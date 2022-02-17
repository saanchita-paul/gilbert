<?php

namespace Reporting\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Modules\Reporting\Services\CalculateEnergyApplicationSummary;
use App\Modules\Reporting\Services\SetDateRage;
use Illuminate\Support\Facades\Log;

class EnergyReport
{
    use SetDateRage;
    private string $startDate;
    private string $endDate;

    private $timezone;

    private array $submissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_ACCEPTED,
        // ConnectionService::STATUS_REJECTED,
        ConnectionService::AC_MANUAL_PROCESSING,
        // ConnectionService::STATUS_CANT_CONNECT,
    ];

    private array $connectedType = [ConnectionService::STATUS_ACCEPTED];
    private array $closedType = [ConnectionApplication::STATUS_CLOSED];
    private array $rejectedType = [
        ConnectionService::STATUS_REJECTED,
        // ConnectionService::STATUS_CANT_CONNECT
    ];

    private array $waitingConnectionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $acManualProcessing = [
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $openType = [
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    private array $notSubmittedType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_REJECTED
    ];



    /**
     * @var MapEnergyReport
     */
    private MapEnergyReport $mapperService;

    public function __construct(string $startDate, string $endDate)
    {
        $this->setDateRange($startDate, $endDate);

        $this->mapperService = new MapEnergyReport();

        $leadBreakDown = [
            "total" => 0,
            "ignite" => 0,
            "our_property" => 0,
            "property_me" => 0,
            "foxie" => 0,
            "hood" => 0,
            "hood_ai" => 0,
        ];
        $data = [
            "total_application" => $leadBreakDown,
            "unassigned_application" => $leadBreakDown,
            "assigned_application" => $leadBreakDown,
            "submitted_application" => $leadBreakDown,
            "conversation_rate" => $leadBreakDown,
            "consent_pending" => $leadBreakDown,
            "closed" => $leadBreakDown,
        ];
    }

    private function getTotalNewApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
            })
            // ->whereNotNull('provider_name')
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getUnassignedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereNull('assigned_to');
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->whereNotNull('provider_name')
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
            ->whereNotIn('status', $this->notSubmittedType)
            ->count();
    }

    private function getAssignedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereNotNull('assigned_to');
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->whereNotNull('provider_name')
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
            ->whereNotIn('status', $this->notSubmittedType)
            ->count();
    }

    private function getTotalClosedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', $this->closedType);
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
            })
            // ->whereNotNull('provider_name');
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    public function totalSubmissions()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->submissionType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
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
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
            ->groupBy('provider_name', 'service_type', 'plan_type')
            ->get()
            ->toArray();
    }

    public function getAcManualProcessing()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->acManualProcessing)
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            ->count();
    }

    public function totalConnected()
    {
        return ConnectionService::query()
            ->selectRaw('provider_name, count(*) as total, service_type, plan_type')
            ->whereNotNull('provider_name')
            ->whereIn('status', $this->connectedType)
            ->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
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
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
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
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_at', '>=', $this->startDate);
                $query->where('created_at', '<=', $this->endDate);
                $query->whereNotIn('status', $this->closedType);
            })
            // ->where('updated_at', '>=', $this->startDate)
            // ->where('updated_at', '<=', $this->endDate)
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
        $data = ConnectionApplication::selectRaw("count(*) as total, status, source")
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate)
            ->where(function ($query) {
                $query->whereHas('connectionServices', function ($q) {
                    $q->whereIn('service_type', [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS]);
                });
                $query->orWhereDoesntHave('connectionServices');
            })
            ->groupBy('status', 'source')
            ->get();

        $appSummary = CalculateEnergyApplicationSummary::getSummary($data->toArray());

        return array_merge([
            "total_new_application" => $this->getTotalNewApplication(), #todo: need to remove this
            "unassigned_application" => $this->getUnassignedApplication(), #todo: need to remove this
            "assigned_application" => $this->getAssignedApplication(), #todo: need to remove this
            "total_consent_pending" => 0, #todo: need to remove this
            "total_closed" => $this->getTotalClosedApplication(), #todo: need to remove this
            'successful_submission' => $this->mapperService->setEnergyData($this->totalSubmissions())->getReportData(),
            'waiting_for_connection' => $this->mapperService->setEnergyData($this->totalWaitingForConnection())->getReportData(),
            "ac_manual_processing" => $this->getAcManualProcessing(),
            "manual_processing" => 0,
            'connected' => $this->mapperService->setEnergyData($this->totalConnected())->getReportData(),
            'rejected' => $this->mapperService->setEnergyData($this->totalRejected())->getReportData(),
            'declined' => $this->mapperService->setEnergyData($this->totalDeclined())->getReportData(),
        ], ['application_summary' => $appSummary]);
    }
}
