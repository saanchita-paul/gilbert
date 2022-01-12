<?php

namespace Reporting\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

/**
 *
 */
class MapWaterReport
{
    /**
     * @var array
     */
    const REPORT_BLUEPRINT  = [
        'total' => 0,
        'great_western_water' => 0,
        'south_eastern_water' => 0,
        'yarra_valley_water' => 0,
        'manual_submission' => 0,
        'automated_submission' => 0,
    ];

    /**
     * @var array
     */
    private array $reportData;
    /**
     * @param array $waterData
     */
    public function __construct(private array $waterData = [])
    {
        $this->resetReportData()->calculate();
    }

    /**
     * @param array $waterData
     */
    public function setWaterData(array $waterData): MapWaterReport
    {
        $this->waterData = $waterData;
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
     * @return MapwaterReport
     */
    public function calculate(): static
    {
        foreach ($this->waterData as $datum) {
            $this->reportData["total"] += $datum->total;
            $this->countWater((array) $datum);
            $this->countManualAndAutomated((array) $datum);
        }
        return $this;
    }

    public function countManualAndAutomated(array $data){
        match ($data['is_auto_water_submit']) {
            1 => $this->reportData["automated_submission"] += $data['total'],
            0 => $this->reportData["manual_submission"] += $data['total'],
            default => 0
        };
    }

    /**
     * @param array $data
     * @return void
     */
    private function countWater(array $data)
    {
        match ($data['provider_name']) {
            // 'greater_western_water' => $this->reportData["great_western_water"] += $data['total'],
            // 'south_east_water' => $this->reportData["south_eastern_water"] += $data['total'],
            // 'yarra_valley_water' => $this->reportData["yarra_valley_water"] += $data['total'],
            default => $this->reportData["great_western_water"] += $data['total'],
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
