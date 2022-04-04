<?php

namespace App\Services\Utility;

use App\Models\ConnectionApplication;
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
            ConnectionService::STATUS_CANT_CONNECT => 'REJECTED',
            ConnectionService::AC_MANUAL_PROCESSING => 'MANUAL_PROCESSING',
            ConnectionService::STATUS_FAILED => 'FAILED',
            default => 'NULL',
        };
    }

    public static function getApplicationStatusAsText(?int $status): string|null
    {
        return match ($status) {
            ConnectionApplication::STATUS_UNASSIGNED => 'UN_ASSIGNED',
            ConnectionApplication::STATUS_ASSIGNED => 'ASSIGNED',
            ConnectionApplication::STATUS_ESCALATED => 'ESCALATED',
            ConnectionApplication::STATUS_SUBMITTED => 'SUBMITTED',
            ConnectionApplication::STATUS_ACCEPTED => 'ACCEPTED',
            ConnectionApplication::STATUS_REJECTED => 'REJECTED',
            ConnectionApplication::STATUS_EA_PROCESSINF => 'ENERGY_SUBMIT',
            ConnectionApplication::STATUS_CLOSED => 'CLOSED',
            20 => 'CONSENT_PENDING',
            default => 'NULL',
        };
    }

    public static function getUtilityStatusAsText(?int $status): string|null
    {
        return match ($status) {
            ConnectionService::STATUS_UNASSIGNED => 'UN_ASSIGNED',
            ConnectionService::STATUS_ASSIGNED => 'ASSIGNED',
            ConnectionService::STATUS_ESCALATED => 'ESCALATED',
            ConnectionService::STATUS_SUBMITTED => 'SUBMITTED',
            ConnectionService::STATUS_ACCEPTED => 'ACCEPTED',
            ConnectionService::STATUS_REJECTED => 'REJECTED',
            ConnectionService::STATUS_EA_PROCESSINF => 'NOT_SUBMITTED',
            ConnectionService::STATUS_ENERGY_SUBMIT => 'SUBMITTED',
            ConnectionService::STATUS_CLOSED => 'CLOSED',
            ConnectionService::STATUS_CANT_CONNECT => 'REJECTED',
            ConnectionService::STATUS_NEEDS_MORE_INFO => 'NEED_MORE_INFO',
            ConnectionService::AC_MANUAL_PROCESSING => 'MANUAL_PROCESSING',
            ConnectionService::STATUS_FAILED => 'FAILED',
            default => 'NULL',
        };
    }
}
