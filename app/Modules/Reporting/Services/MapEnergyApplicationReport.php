<?php

namespace App\Modules\Reporting\Services;

use App\Models\ConnectionApplication;

class MapEnergyApplicationReport
{
    const LEAD_BREAKDOWN = [
        "total" => 0,
        "ignite" => 0,
        "our_property" => 0,
        "property_me" => 0,
        "foxie" => 0,
        "hood" => 0,
        "hood_ai" => 0,
    ];

    private array $applicationSummary = [
        "all" => self::LEAD_BREAKDOWN,
        "unassigned" => self::LEAD_BREAKDOWN,
        "assigned" => self::LEAD_BREAKDOWN,
        "submitted" => self::LEAD_BREAKDOWN,
        "conversation_rate" => self::LEAD_BREAKDOWN,
        "consent_pending" => self::LEAD_BREAKDOWN,
        "closed" => self::LEAD_BREAKDOWN,
    ];

    public function map(array $applicationData): array
    {
        return $this->applicationSummary;
        foreach ($applicationData as $datum) {
            match (data_get($datum, 'status')) {
                ConnectionApplication::STATUS_UNASSIGNED => $this->mapStatus('unassigned', $datum),
                ConnectionApplication::STATUS_ASSIGNED => $this->mapStatus('assigned', $datum),
                ConnectionApplication::STATUS_EA_PROCESSINF,
                ConnectionApplication::STATUS_SUBMITTED => $this->mapStatus('submitted', $datum),
                20 => $this->mapStatus('consent_pending', $datum), #todo: replace with proper constant when merging to consent_tracker branch
                ConnectionApplication::STATUS_CLOSED => $this->mapStatus('closed', $datum),
                default => self::LEAD_BREAKDOWN
            };
        }
        return $this->applicationSummary;
    }

    /**
     * Calculation summary based on status
     *
     * @param string $statusKey
     * @param array $item
     *
     * @return void
     */
    private function mapStatus(string $statusKey, array $item)
    {
        if (!is_int($item['total'])) {
            return;
        }

        $this->applicationSummary[$statusKey]["total"] += $item['total'];
        $this->applicationSummary["total"]["total"] += $item['total'];

        $sourceKey = $this->mapSource($item['source']);
        if ($sourceKey) {
            $this->applicationSummary[$statusKey][$sourceKey] += $item['total'];
            $this->applicationSummary["total"][$sourceKey] += $item['total'];
        }
    }

    /**
     * Mapping source key
     *
     * @param int|null $source
     *
     * @return string|null
     */
    public function mapSource(?int $source): ?string
    {
        return match ($source) {
            ConnectionApplication::SOURCE_FOXIE => 'foxie',
            ConnectionApplication::SOURCE_HOOD => 'hood',
            ConnectionApplication::SOURCE_IGNITE => 'ignite',
            ConnectionApplication::SOURCE_OUR_PROPERTY => 'our_property',
            ConnectionApplication::SOURCE_PROPERTY_ME => 'property_me',
            10 => 'hood_ai', #todo: replace with proper constant
            default => null,
        };
    }
}
