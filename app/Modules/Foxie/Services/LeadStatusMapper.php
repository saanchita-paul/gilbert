<?php

namespace App\Modules\Foxie\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

/**
 *
 */
class LeadStatusMapper
{
    /**
     *
     */
    const STATUS_SUCCESS = 'success';
    /**
     *
     */
    const STATUS_REJECTED = 'Rejected';
    /**
     *
     */
    const STATUS_ACCEPTED = 'Accepted';
    /**
     *
     */
    const STATUS_IN_PROGRESS = 'In Progress';

    /**
     * hood and foxie water-status mapper
     */
    const WATER_STATUS_MAPPER = [
        ConnectionService::WATER_STATUS_CANT_CONNECT => self::STATUS_REJECTED,
        ConnectionService::WATER_STATUS_CONNECTED => self::STATUS_ACCEPTED,
        ConnectionService::WATER_STATUS_NEED_INFO => self::STATUS_IN_PROGRESS,
        ConnectionService::WATER_STATUS_SUBMITTED => self::STATUS_SUCCESS,
        ConnectionService::WATER_STATUS_IN_PROGRESS => self::STATUS_IN_PROGRESS,

    ];

    const PROVIDER_NAME_WESTERN_WATER = "Western Water";
    const PROVIDER_NAME_SOUTHEAST_WATER = "South East Water";
    const PROVIDER_NAME_YARRA_VALLEY_WATER = "Yarra Valley Water";
          
    
    const PROVIDER_NAME_MAPPER =[
        'greater_western_water' => self::PROVIDER_NAME_WESTERN_WATER,
        'south_east_water' => self::PROVIDER_NAME_SOUTHEAST_WATER,
        'yarra_valley_water' => self::PROVIDER_NAME_YARRA_VALLEY_WATER,
    ];

    /**
     * hood and foxie energy-status mapper
     */
    const ENERGY_STATUS_MAPPER = [
        ConnectionApplication::STATUS_UNASSIGNED => self::STATUS_IN_PROGRESS,
        ConnectionApplication::STATUS_ASSIGNED => self::STATUS_IN_PROGRESS,
        ConnectionApplication::STATUS_SUBMITTED => self::STATUS_SUCCESS,
        ConnectionApplication::STATUS_REJECTED => self::STATUS_REJECTED,
        ConnectionApplication::STATUS_ACCEPTED => self::STATUS_ACCEPTED,
    ];

    /**
     * Lead ID
     *
     * @var int
     */
    private int $leadId;

    /**
     * All statuses
     *
     * @var array
     */
    private array $statuses = [];

    /**
     * Lead (application)
     *
     * @var ConnectionApplication | null
     */
    private ?ConnectionApplication $lead;


    /**
     * @throws \Exception
     */
    public function __construct(int $leadId)
    {

        $this->leadId = $leadId;
        $this->loadLead()->mapStatuses();

    }

    /**
     * getting statuses array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->statuses;
    }

    /**
     * @return $this
     */
    private function loadLead()
    {
        $this->lead = ConnectionApplication::findOrFail($this->leadId);
        return $this;
    }

    /**
     * @throws \Exception
     */
    private function mapStatuses()
    {
        /** @var ConnectionService[] $services */
        $services = $this->lead->connectionServices;

        foreach ($services as $service) {
            match ($service->service_type) {
                ConnectionService::TYPE_ELECTRICITY => $this->energyStatus($this->lead->status, 'elec'),
                ConnectionService::TYPE_GAS => $this->energyStatus($this->lead->status, 'gas'),
                ConnectionService::TYPE_WATER => $this->waterStatus($service, 'water'),
                ConnectionService::TYPE_INTERNET => $this->energyStatus($service->status, 'internet'),
                default => throw new \Exception("unknown service $service->service_type")
            };
        }
    }


    /**
     *
     * @param $status
     * @param $statusKey
     */
    private function energyStatus($status, $statusKey): void
    {
        $status = LeadStatusMapper::ENERGY_STATUS_MAPPER[$status] ?? 'Other'; //todo:

        if ($status === self::STATUS_ACCEPTED) {
            $this->statuses[$statusKey . 'Retailer'] = 'EA';
        }

        $this->statuses[$statusKey . 'Status'] = $status;
    }

    /**
     * @param $service
     * @param string $statusKey
     */
    private function waterStatus($service, string $statusKey): void
    {
        $status = LeadStatusMapper::WATER_STATUS_MAPPER[$service->status] ?? 'Other'; //todo:

        if ($status === self::STATUS_ACCEPTED) {
            $this->statuses[$statusKey . 'Retailer'] = !empty($service->provider_name)
                ? $this->waterProviderNameMapper($service->provider_name)
                : 'South East Water';
        }

        $this->statuses[$statusKey . 'Status'] = $status;
    }

    private function waterProviderNameMapper($name){
        return self::PROVIDER_NAME_MAPPER[$name] ?? '';
    }
}
