<?php

namespace App\Services\Agency;

use App\Models\ConnectionService;
use JetBrains\PhpStorm\ArrayShape;

class UpdatedWaterStatus
{
    const STATUS_MAPPING = [
        "RECEIVED" => [
            'status' => ConnectionService::WATER_STATUS_SUBMITTED,
            'reason' => ''
        ],
        "CONFIRMED" => [
            'status' => ConnectionService::WATER_STATUS_CONNECTED,
            'reason' => ''
        ],
        "ADVANCE" => [
            'status' => ConnectionService::WATER_STATUS_SUBMITTED,
            'reason' => ''
        ],
        "IN_TRANSIT" => [
            'status' => ConnectionService::WATER_STATUS_SUBMITTED,
            'reason' => ''
        ],
        "CANCELLED" => [
            'status' => ConnectionService::WATER_STATUS_CANT_CONNECT,
            'reason' => ''
        ],
        "NOT_SEPERATELY_METERED" => [
            'status' => ConnectionService::WATER_STATUS_CANT_CONNECT,
            'reason' => 'the water is likely billed by a strata or is possibly a granny flat'
        ],
        "CUSTOMER_COMPLETED" => [
            'status' => ConnectionService::WATER_STATUS_CANT_CONNECT,
            'reason' => 'The customer (or their partner) has already made an application for that address'
        ]
    ];


    /**
     * @param string|null $fcStatus
     * @return array|null
     */
    #[ArrayShape(['status' => "int", 'reason' => "string"])]
    public static function mapFromFCStatus(?string $fcStatus): ?array
    {
        if (isset(UpdatedWaterStatus::STATUS_MAPPING[$fcStatus])) {
            return UpdatedWaterStatus::STATUS_MAPPING[$fcStatus];
        }
        info("[UpdatedWaterStatus:mapFromFCStatus]: No status matched for value: $fcStatus");
        return  null;
    }

    /**
     * @param int $leadId
     * @param int $status
     * @param string|null $reason
     */
    public static function updateStatus(int $leadId, int $status, ?string $reason)
    {
        $waterServiceBuilder =  ConnectionService::query()
            ->where('connection_application_id', $leadId)
            ->where('service_type', ConnectionService::TYPE_WATER);

        if(is_null($waterServiceBuilder->first())) {
            ConnectionService::create(['connection_application_id'=> $leadId, 'service_type'=> ConnectionService::TYPE_WATER]);
        }
        $waterServiceBuilder->update(['status' => $status, 'reason' => $reason]);

        if($status == ConnectionService::WATER_STATUS_CONNECTED) {
            $waterServiceBuilder->update(['accepted_at' => now()]);
        }
    }
}
