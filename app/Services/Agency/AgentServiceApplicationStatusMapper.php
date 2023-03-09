<?php

namespace App\Services\Agency;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class AgentServiceApplicationStatusMapper
{
    /**
     * Service default status
     */
    const DEFAULT_STATUS = [
        'status_name' => 'Not Applicable',
        'description' => 'The connection is not required or was not selected by the customer.'
    ];

    /**
     * @var array|string[]
     */
    protected array $services = [
        'power' => self::DEFAULT_STATUS,
        'gas' => self::DEFAULT_STATUS,
        'water' => self::DEFAULT_STATUS,
        'internet' => self::DEFAULT_STATUS,
    ];

    /**
     * @param $services_status
     * @param $tenancyType
     * @param $state
     * @return array|string[]
     */
    public function getAgentServiceApplicationStatus($services_status, $tenancyType, $state): array
    {
        foreach ($services_status as $service) {
            match ($service->service_type) {
                'power', 'gas' => $this->mapEnergyStatus($service->service_type, $service->status),
                'water' => $this->mapWaterStatus($service->service_type, $service->status, $tenancyType, $state),
                #'internet' => self::DEFAULT_STATUS
                default => self::DEFAULT_STATUS
            };
        }
        return $this->services;
    }

    /**
     * @param string $serviceType
     * @param int|null $status
     * @return void
     */
    private function mapEnergyStatus(string $serviceType, ?int $status): void
    {
        $value = match ($status) {
            ConnectionService::STATUS_ACCEPTED => ['status_name' => 'Confirmed', 'description' => 'The connection has been processed and accepted by the provider.'],
            ConnectionService::STATUS_REJECTED,
            ConnectionService::STATUS_CANT_CONNECT => ['status_name' => 'Declined', 'description' => 'The connection has been processed but was declined by the provider.'],
            ConnectionService::STATUS_SUBMITTED,
            ConnectionService::STATUS_ENERGY_SUBMIT,
            ConnectionService::AC_MANUAL_PROCESSING => ['status_name' => 'Awaiting Confirmation', 'description' => 'The connection is processed and we are awaiting confirmation from the provider.'],
            ConnectionService::STATUS_EA_PROCESSINF => ['status_name' => 'Not Applicable', 'description' => 'The connection is not required or is yet to be selected by the customer.'],
            default => self::DEFAULT_STATUS
        };

        $this->services[$serviceType] = $value;
    }

    /**
     * @param string|null $serviceType
     * @param int|null $status
     * @param int|null $tenancyType
     * @param string|null $state
     *
     */
    private function mapWaterStatus(?string $serviceType, ?int $status, ?int $tenancyType, ?string $state)
    {
        $notApplicable = ['status_name' => 'Not Applicable', 'description' => 'The connection is not required or is yet to be selected by the customer.'];

        if ($tenancyType == ConnectionApplication::TENANCY_TYPE_HOME_OWNER || $state !== 'Victoria') {
            $this->services[$serviceType] = $notApplicable;
            return;
        }

        $value = match ($status) {
            ConnectionService::STATUS_SUBMITTED,
            ConnectionService::STATUS_ACCEPTED,
            ConnectionService::STATUS_ENERGY_SUBMIT,
                # ConnectionService::STATUS_FAILED = Water manual processing
            ConnectionService::STATUS_FAILED => ['status_name' => 'Confirmed', 'description' => 'The connection has been processed and accepted by the provider.'],
            ConnectionService::STATUS_REJECTED,
            ConnectionService::STATUS_CANT_CONNECT => ['status_name' => 'Declined', 'description' => 'The connection has been processed but was declined by the provider.'],
            ConnectionService::STATUS_EA_PROCESSINF => $notApplicable,
            default => self::DEFAULT_STATUS
        };

        $this->services[$serviceType] = $value;
    }

}
