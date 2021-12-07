<?php

namespace App\Services\Sales;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Exception;

class SalesStatusMapper
{
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_AC_MANUAL_PROCESSING = 'AC_MANUAL_PROCESSING';
    const STATUS_MANUAL_PROCESSING = 'MANUAL_PROCESSING';
    const STATUS_ACCEPTED = 'ACCEPTED';
    const STATUS_REJECTED = 'REJECTED';

    /**
     * Mapping EA status to Gilbert status
     *
     * @param string|null $status
     *
     * @return int
     *
     * @throws Exception
     */
    public static function EAToGilbert(?string $status): int
    {
        return match ($status) {
            static::STATUS_PROCESSING  => ConnectionService::STATUS_EA_PROCESSINF,
            static::STATUS_AC_MANUAL_PROCESSING,
            static::STATUS_MANUAL_PROCESSING  => ConnectionService::AC_MANUAL_PROCESSING,
            static::STATUS_ACCEPTED  => ConnectionService::STATUS_ACCEPTED,
            static::STATUS_REJECTED  => ConnectionService::STATUS_REJECTED,
            default => throw new Exception("[SalesStatusMapper:EAToGilbert] Unknown EA STATUS -> $status")
        };
    }

}
