<?php

namespace App\Services\Utility;

use App\Models\ConnectionService;

class GilbertStatusMapper
{
    /**
     * Map Connection Service status to text
     *
     * @param int|null $status
     *
     * @return int|string|null
     */
    public static function getStatusAsText(?int $status): int|string|null
    {
        return match ($status) {
            ConnectionService::STATUS_EA_PROCESSINF => 'NOT_SUBMITTED',
            ConnectionService::STATUS_ACCEPTED => 'ACCEPTED',
            ConnectionService::STATUS_CLOSED => 'CLOSED',
            ConnectionService::STATUS_ENERGY_SUBMIT,
            ConnectionService::STATUS_SUBMITTED => 'IN_PROGRESS',
            ConnectionService::STATUS_REJECTED => 'REJECTED',
            ConnectionService::STATUS_CANT_CONNECT => 'FAILED',
            ConnectionService::AC_MANUAL_PROCESSING => 'MANUAL_PROCESSING',
            default => 'NULL',
        };
    }
}
