<?php

namespace App\Services\Agency;


use App\Models\ConnectionService;

class AgentServiceApplicationStatusMapper
{
    const DEFAULT_STATUS = [
        'status_name' => 'Not Applicable',
        'description' => 'The connection is not required or was not selected by the customer.'
    ];

    protected array $services = [
        'power' => self::DEFAULT_STATUS,
        'gas' => self::DEFAULT_STATUS,
        'water' => self::DEFAULT_STATUS,
        'internet' => self::DEFAULT_STATUS,
    ];

    public function getAgentServiceApplicationStatus($services_status)
    {
        foreach ($services_status as $service) {
            match ($service->service_type) {
                'power', 'gas' => $this->mapEnergyStatus($service->service_type, $service->status),
                'water' => $this->mapWaterStatus($service->service_type, $service->status),
                'internet' => self::DEFAULT_STATUS
            };
        }
        return $this->services;
    }

    private function mapEnergyStatus(string $serviceType, ?int $status): void
    {
        $value = match ($status) {
            ConnectionService::STATUS_SUBMITTED, ConnectionService::STATUS_ACCEPTED => ['status_name' => 'Confirmed', 'description' => 'The connection has been processed and accepted by the provider.'],
            ConnectionService::STATUS_REJECTED, ConnectionService::STATUS_CANT_CONNECT => ['status_name' => 'Declined', 'description' => 'The connection has been processed but was declined by the provider.'],
            ConnectionService::AC_MANUAL_PROCESSING => ['status_name' => 'Awaiting Confirmation', 'description' => 'The connection is processed and we are awaiting confirmation from the provider.'],
            default => self::DEFAULT_STATUS
        };

        $this->services[$serviceType] = $value;
    }

    private function mapWaterStatus(string $serviceType, ?int $status): void
    {
        $value = match ($status) {
            ConnectionService::STATUS_ACCEPTED => ['status_name' => 'Confirmed', 'description' => 'The connection has been processed and accepted by the provider.'],
            ConnectionService::STATUS_REJECTED => ['status_name' => 'Manual Processing', 'description' => 'There was an issue processing the water connection automatically, but our team is fixing it.'],
            default => self::DEFAULT_STATUS
        };

        $this->services[$serviceType] = $value;
    }

}
