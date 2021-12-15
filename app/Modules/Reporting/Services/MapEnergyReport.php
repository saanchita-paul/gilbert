<?php

namespace Reporting\Services;

class MapEnergyReport
{
    private array $reportData = [
        'total' => 0,
        'ea_gas_total_plan' => 0,
        'ea_gas_no_frills' => 0,
        'ea_gas_basic_plan' => 0,
        'ea_power_total_plan' => 0,
        'ea_power_no_frills' => 0,
        'ea_power_basic_plan' => 0,
        'sumo_power_freedom' => 0,
        'sumo_gas_freedom' => 0,
    ];
    public function __construct(private array $energyData)
    {
    }

    public function run()
    {
        foreach ($this->energyData as $datum) {

        }
    }

    private function countTotal(array $data)
    {

    }
    private function countEAPower()
    {

    }
    private function countEAGas()
    {

    }
    private function countSumoGas()
    {

    }
    private function countSumoEnergy()
    {

    }

}
