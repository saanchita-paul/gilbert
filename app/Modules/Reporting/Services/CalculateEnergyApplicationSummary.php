<?php

namespace App\Modules\Reporting\Services;

use App\Models\ConnectionApplication;

/**
 *
 */
class CalculateEnergyApplicationSummary
{
    /**
     *
     */
    const LEAD_BREAKDOWN = [
        "total" => 0,
        "ignite" => 0,
        "our_property" => 0,
        "property_me" => 0,
        "foxie" => 0,
        "hood" => 0,
        "hood_ai" => 0,
        "t_app" => 0,
    ];

    /**
     * @var array|\int[][]
     */
    private array $applicationSummary = [
        "all" => self::LEAD_BREAKDOWN,
        "unassigned" => self::LEAD_BREAKDOWN,
        "assigned" => self::LEAD_BREAKDOWN,
        "submitted" => self::LEAD_BREAKDOWN,
        "conversation_rate" => self::LEAD_BREAKDOWN,
        "consent_pending" => self::LEAD_BREAKDOWN,
        "closed" => self::LEAD_BREAKDOWN,
        "escalated" => self::LEAD_BREAKDOWN,
    ];

    /**
     * @param array $applicationData
     */
    public function __construct(private array $applicationData) {
        $this->mapStatuses()->mapConversions();
    }

    /**
     * @return array
     */
    public function getApplicationSummary(): array
    {
        return $this->applicationSummary;
    }


    /**
     * Ca
     * @param array $applicationData
     * @return array
     */
    public static function getSummary(array $applicationData): array
    {
        return (new static($applicationData))->getApplicationSummary();
    }

    /**
     * @return $this
     */
    private function mapStatuses(): static
    {
        foreach ($this->applicationData as $datum) {
            match (data_get($datum, 'status')) {
                ConnectionApplication::STATUS_UNASSIGNED => $this->calculateCount('unassigned', $datum),
                ConnectionApplication::STATUS_ASSIGNED => $this->calculateCount('assigned', $datum),
                ConnectionApplication::STATUS_EA_PROCESSINF,
                ConnectionApplication::STATUS_REJECTED,
                ConnectionApplication::STATUS_ACCEPTED,
                ConnectionApplication::STATUS_SUBMITTED => $this->calculateCount('submitted', $datum),
                20 => $this->calculateCount('consent_pending', $datum), #todo: replace with proper constant when merging to consent_tracker branch
                ConnectionApplication::STATUS_CLOSED => $this->calculateCount('closed', $datum),
                ConnectionApplication::STATUS_ESCALATED => $this->calculateCount('escalated', $datum),
                default => self::LEAD_BREAKDOWN
            };
        }

        return $this;
    }

    /**
     * Calculation summary based on status
     *
     * @param string $statusKey
     * @param array $item
     *
     * @return void
     */
    private function calculateCount(string $statusKey, array $item)
    {
        if (!is_int($item['total'])) {
            return;
        }

        $this->applicationSummary[$statusKey]["total"] += $item['total'];
        $this->applicationSummary["all"]["total"] += $item['total'];

        $sourceAsString = $this->mapSourceToString($item['source']);
        if ($sourceAsString) {
            $this->applicationSummary[$statusKey][$sourceAsString] += $item['total'];
            $this->applicationSummary["all"][$sourceAsString] += $item['total'];
        }
    }

    /**
     * Mapping source key
     *
     * @param int|null $source
     *
     * @return string|null
     */
    private function mapSourceToString(?int $source): ?string
    {
        return match ($source) {
            ConnectionApplication::SOURCE_FOXIE => 'foxie',
            ConnectionApplication::SOURCE_HOOD => 'hood',
            ConnectionApplication::SOURCE_IGNITE => 'ignite',
            ConnectionApplication::SOURCE_OUR_PROPERTY => 'our_property',
            ConnectionApplication::SOURCE_PROPERTY_ME => 'property_me',
            10 => 'hood_ai', #todo: replace with proper constant
            ConnectionApplication::SOURCE_T_APP => 't_app',
            default => null,
        };
    }

    /**
     * Calculation conversation rate breakdown
     *
     * @return $this
     */
    private function mapConversions(): static
    {
        foreach (array_keys(self::LEAD_BREAKDOWN) as $source) {
            $this->calculateConversionRate($source);
        }

        return $this;
    }

    /**
     * updating rate in $this->applicationSummary
     *
     * @param string|null $sourceAsString
     *
     * @return void
     */
    private function calculateConversionRate(?string $sourceAsString): void
    {
        $total = $this->applicationSummary["all"][$sourceAsString];
        $submitted = $this->applicationSummary["submitted"][$sourceAsString];

        if ($total > 0) {
            $this->applicationSummary["conversation_rate"][$sourceAsString] =  number_format(
                ($submitted / $total) * 100,
                2
            );
        }
    }
}
