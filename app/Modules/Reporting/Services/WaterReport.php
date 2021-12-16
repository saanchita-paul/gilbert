<?php

namespace Reporting\Services;

use DB;
use Carbon\Carbon;
use App\Models\ConnectionService;
use JetBrains\PhpStorm\ArrayShape;
use App\Models\ConnectionApplication;
use Reporting\Services\MapWaterReport;
use Doctrine\DBAL\Driver\IBMDB2\Connection;

class WaterReport
{
    private string $startDate;
    private string $endDate;

    private int $timezone;

    private array $submisssionType = [
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::WATER_STATUS_SUBMITTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::STATUS_CANT_CONNECT,
        ConnectionService::AC_MANUAL_PROCESSING
    ];

    private array $conversionsType = [ConnectionService::WATER_STATUS_CONNECTED];
    private array $closedType = [ConnectionApplication::STATUS_CLOSED];
    private array $rejectedType = [ConnectionService::STATUS_CLOSED];

    private array $openType = [
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    /**
     * @var MapWaterReport
     */
    private MapWaterReport $mapperService;

    public function __construct(string $startDate, string $endDate)
    {
        $this->timezone = env("TIME_ZONE", 11) ?? 11;

        $this->startDate = Carbon::parse($startDate, tz: $this->timezone)->setTimezone(0)->toDateTimeString();
        $this->endDate = Carbon::parse($endDate, tz: $this->timezone)
            ->addHours(11)
            ->addMinutes(59)
            ->addSeconds(59)
            ->setTimezone(0)->toDateTimeString();

        $this->mapperService = new MapWaterReport();
    }

    public function totalSubmissions()
    {
        return DB::table('connection_applications')
                ->selectRaw('provider_name , is_auto_water_submit,  count(*) as total')
                ->join('connection_services' , 'connection_services.connection_application_id' , '=' , 'connection_applications.id')
                ->whereIn('connection_services.service_type', [ConnectionService::TYPE_WATER])
                ->whereIn('connection_applications.status', $this->submisssionType)
                ->where('submitted_at', '>=', $this->startDate)
                ->where('submitted_at', '<=', $this->endDate)
                ->groupBy( 'provider_name', 'is_auto_water_submit')
                ->get()
                ->toArray();

    }

    public function totalConversions()
    {
        return DB::table('connection_applications')
        ->selectRaw('provider_name , is_auto_water_submit,  count(*) as total')
        ->join('connection_services', 'connection_services.connection_application_id', '=', 'connection_applications.id')
        ->whereIn('connection_services.service_type', [ConnectionService::TYPE_WATER])
        ->whereIn('connection_applications.status', $this->conversionsType)
        ->where('submitted_at', '>=', $this->startDate)
        ->where('submitted_at', '<=', $this->endDate)
        ->groupBy('provider_name', 'is_auto_water_submit')
        ->get()
        ->toArray();

    }

    public function totalRejected()
    {
        return DB::table('connection_applications')
        ->selectRaw('provider_name , is_auto_water_submit,  count(*) as total')
        ->join('connection_services', 'connection_services.connection_application_id', '=', 'connection_applications.id')
        ->whereIn('connection_services.service_type', [ConnectionService::TYPE_WATER])
        ->whereIn('connection_applications.status', $this->rejectedType)
        ->where('submitted_at', '>=', $this->startDate)
        ->where('submitted_at', '<=', $this->endDate)
        ->groupBy('provider_name', 'is_auto_water_submit')
        ->get()
        ->toArray();
    }

    public function totalDeclined()
    {
        $total = ConnectionService::whereIn('service_type', [ConnectionService::TYPE_WATER])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', $this->rejectedType);
            })
            ->whereHas('reasons', function ($query) {
                $query->where('reason_code', 'CREDIT_CHECK');
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
        return [
            'total' => $total
        ];
    }

    private function getTotalOpenApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_WATER])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', $this->openType);
            })
            ->where('updated_at', '>=', $this->startDate)
            ->where('updated_at', '<=', $this->endDate)
            ->count();
    }

    private function getTotalClosedApplication()
    {
        return ConnectionService::whereIn('service_type', [ConnectionService::TYPE_WATER])
            ->whereHas('connectionApplication', function ($query) {
                $query->whereIn('status', [$this->closedType])
                        ->where('closed_at', '>=', $this->startDate)
                        ->where('closed_at', '<=', $this->endDate);
                })
                ->count();
    }

    public function getWaterReport(): array
    {
        return [
            'submission' => $this->mapperService->setWaterData( $this->totalSubmissions() )->getReportData(),
            'conversions' => $this->mapperService->setWaterData( $this->totalConversions() )->getReportData(),
            'rejected' => $this->mapperService->setWaterData($this->totalRejected())->getReportData(),
            'declined' => $this->totalDeclined(),
            "total_open_application" => $this->getTotalOpenApplication(),
            "total_consent_pending" => 0,
            "total_closed" => $this->getTotalClosedApplication(),
        ];
    }
}
