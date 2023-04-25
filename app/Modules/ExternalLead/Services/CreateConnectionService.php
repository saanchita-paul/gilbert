<?php

namespace ExternalLead\Services;

use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Services\AddressMapperService;
use App\Models\ConnectionService;

class CreateConnectionService
{
    public const MUST_SERVICES = [
        'water'
    ];

    public function save(ConnectionApplication $app, array $data)
    {
        $createdServices = [];
        $requestedServices = $data['utility_services'] ?? [];

        foreach (self::MUST_SERVICES as $serviceType) {
            if (!in_array($serviceType, $requestedServices)) {
                $requestedServices[] = $serviceType;
            }
        }

        foreach ($requestedServices as $service) {
            $connectionService = new ConnectionService();
            $connectionService->service_type = strtolower($service);
            $connectionService->status = ConnectionService::STATUS_EA_PROCESSINF;
            $connectionService->connection_application_id = $app->id;
            $connectionService->save();

            $createdServices[$connectionService->service_type] = $connectionService;
        }

        return $createdServices;
    }
}
