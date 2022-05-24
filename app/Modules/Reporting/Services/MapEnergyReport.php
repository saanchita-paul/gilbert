<?php

namespace Reporting\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

/**
 *
 */
class MapEnergyReport
{
    /**
     * @var array
     */
    const REPORT_BLUEPRINT  = [
        'total' => 0,
        'ea_gas_total_plan' => 0,
        'ea_gas_no_frills' => 0,
        'ea_gas_basic_plan' => 0,
        'ea_power_total_plan' => 0,
        'ea_power_no_frills' => 0,
        'ea_power_basic_plan' => 0,
        'sumo_power_freedom' => 0,
        'sumo_gas_freedom' => 0,
        'origin_power_home_assist' => 0,
        'origin_gas_home_assist' => 0,
    ];

    /**
     * @var array
     */
    private array $reportData;

    /**
     * @param array $energyData
     */
    public function __construct(private array $energyData = [])
    {
        $this->resetReportData()->calculate();
    }

    /**
     * @param array $energyData
     */
    public function setEnergyData(array $energyData): MapEnergyReport
    {
        $this->energyData = $energyData;
        $this->resetReportData()->calculate();

        return $this;
    }
    /**
     * @return array
     */
    public function getReportData(): array
    {
        return $this->reportData;
    }

    /**
     * @return MapEnergyReport
     */
    public function calculate(): static
    {
        foreach ($this->energyData as $datum) {
            $this->reportData["total"] += $datum['total'];

            if ($datum['service_type'] === ConnectionService::TYPE_ELECTRICITY) {
                $this->countPower($datum);
            }
            if ($datum['service_type'] === ConnectionService::TYPE_GAS) {
                $this->countGas($datum);
            }
        }
        return $this;
    }

    /**
     * @param array $data
     * @return void
     */
    private function countPower(array $data)
    {
        match ($data['plan_type']) {
            ConnectionApplication::PLAN_TYPE_BASIC => $this->reportData["ea_power_basic_plan"] += $data['total'],
            ConnectionApplication::PLAN_TYPE_TOTAL => $this->reportData["ea_power_total_plan"] += $data['total'],
            ConnectionApplication::PLAN_TYPE_NO_FRILLS => $this->reportData["ea_power_no_frills"] += $data['total'],
            'Sumo Freedom' => $this->reportData["sumo_power_freedom"] += $data['total'],
            'origin_home_assist' => $this->reportData["origin_power_home_assist"] += $data['total'],
            default => null
        };
    }

    /**
     *
     * @param array $data
     * @return void
     */
    private function countGas(array $data)
    {
        match ($data['plan_type']) {
            ConnectionApplication::PLAN_TYPE_BASIC => $this->reportData["ea_gas_basic_plan"] += $data['total'],
            ConnectionApplication::PLAN_TYPE_TOTAL => $this->reportData["ea_gas_total_plan"] += $data['total'],
            ConnectionApplication::PLAN_TYPE_NO_FRILLS => $this->reportData["ea_gas_no_frills"] += $data['total'],
            'Sumo Freedom' => $this->reportData["sumo_gas_freedom"] += $data['total'],
            'origin_home_assist' => $this->reportData["origin_gas_home_assist"] += $data['total'],
            default => null
        };
    }


    /**
     * making all values  zero for report
     *
     * @return $this
     */
    private function resetReportData()
    {
        $this->reportData = self::REPORT_BLUEPRINT;
        return $this;
    }


}
