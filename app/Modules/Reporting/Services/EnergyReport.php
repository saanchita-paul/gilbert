<?php

namespace Reporting\Services;

use App\Models\ConnectionService;
use Carbon\Carbon;
use DB;
use JetBrains\PhpStorm\ArrayShape;

class EnergyReport
{
    private string $startDate;
    private string $endDate;

    private int $timezone;

    private array $submisssionType = [ConnectionService::STATUS_SUBMITTED , ConnectionService::STATUS_ENERGY_SUBMIT];
    private array $conversionsType = [ConnectionService::STATUS_ACCEPTED];
    private array $rejectedType    = [ConnectionService::STATUS_REJECTED];

    /**
     * @var MapEnergyReport
     */
    private MapEnergyReport $mapperService;

    public function __construct(string $startDate, string $endDate)
    {
        $this->timezone = env("TIME_ZONE", 11) ?? 11;

        $this->startDate = Carbon::parse($startDate, tz: $this->timezone)->setTimezone(0)->toDateTimeString();
        $this->endDate   = Carbon::parse($endDate, tz: $this->timezone)
            ->addHours(11)
            ->addMinutes(59)
            ->addSeconds(59)
            ->setTimezone(0)->toDateTimeString();
        $this->mapperService = new MapEnergyReport();
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
            ->where('updated_at' , '>=' , $this->startDate)
            ->where('updated_at' , '<=' , $this->endDate)
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

    #[ArrayShape(['submission' => "array", 'conversions' => "array", 'rejected' => "array", 'declined' => "array"])]
    public function getEnergyReport(): array
    {
        return [
            'submission'  => $this->mapperService->setEnergyData($this->totalSubmissions())->getReportData(),
            'conversions' =>  $this->mapperService->setEnergyData($this->totalConversions())->getReportData(),
            'rejected'    =>  $this->mapperService->setEnergyData($this->totalRejected())->getReportData(),
            'declined'    =>  $this->totalDeclined(),
        ];
    }
}
