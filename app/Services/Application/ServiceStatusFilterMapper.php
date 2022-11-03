<?php

namespace App\Services\Application;

use App\Models\ApplicationServiceStatus;

class ServiceStatusFilterMapper
{

    // Application statuses
    const STATUS_UNASSIGNED = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_ESCALATED = 3;
    const STATUS_SUBMITTED = 4;
    const STATUS_ACCEPTED = 5;
    const STATUS_REJECTED = 6;
    const STATUS_EA_PROCESSING = 7;
    const STATUS_CLOSED = 8;
    public static $application_status_mapping = [
        self::STATUS_UNASSIGNED => 'unassigned',
        self::STATUS_ASSIGNED => 'assigned',
        self::STATUS_ESCALATED => 'escalated',
        self::STATUS_SUBMITTED => 'submitted',
        self::STATUS_ACCEPTED => 'accepted',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_EA_PROCESSING => 'processing',
        self::STATUS_CLOSED => 'closed',
    ];

    // Service statuses
    const STATUS_CANT_CONNECT = 9;
    const STATUS_NEEDS_MORE_INFO = 10;
    const AC_MANUAL_PROCESSING = 11;
    const STATUS_ENERGY_SUBMIT = 12;
    const STATUS_FAILED = 13;
    public static $service_status_mapping = [
        self::STATUS_UNASSIGNED => 'unassigned',
        self::STATUS_ASSIGNED => 'assigned',
        self::STATUS_ESCALATED => 'escalated',
        self::STATUS_SUBMITTED => 'submitted',
        self::STATUS_ACCEPTED => 'accepted',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_EA_PROCESSING => 'processing',
        self::STATUS_ENERGY_SUBMIT => 'processing',
        self::STATUS_CLOSED => 'closed',
        self::STATUS_CANT_CONNECT => "can't_connect",
        self::STATUS_NEEDS_MORE_INFO => 'need_more_info',
        self::AC_MANUAL_PROCESSING => 'ac_manual_precessing',
        self::STATUS_FAILED => 'failed',
    ];

    /**
     * Filter changeable service statuses by application status
     */
    const FILTER_STATUS = [
        self::STATUS_UNASSIGNED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_FAILED
        ],
        self::STATUS_ASSIGNED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_FAILED
        ],
        self::STATUS_SUBMITTED => [
            self::STATUS_ENERGY_SUBMIT,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_FAILED,

        ],
        self::STATUS_ESCALATED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
        self::STATUS_CLOSED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
    ];


    /*
     * Filter changeable service statuses by service status
     */
    const FILTER_SERVICE_STATUS = [
        self::STATUS_EA_PROCESSING => [
            self::STATUS_FAILED,
            self::STATUS_EA_PROCESSING
        ],
        self::STATUS_FAILED => [
            self::STATUS_FAILED,
            self::STATUS_EA_PROCESSING,
        ],
        self::STATUS_SUBMITTED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,

        ],
        self::STATUS_ENERGY_SUBMIT => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
        self::AC_MANUAL_PROCESSING => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
        self::STATUS_ACCEPTED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
        self::STATUS_REJECTED => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
        self::STATUS_CANT_CONNECT => [
            self::STATUS_EA_PROCESSING,
            self::STATUS_SUBMITTED,
            self::STATUS_ENERGY_SUBMIT,
            self::AC_MANUAL_PROCESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANT_CONNECT,
        ],
    ];

    public function getStatusesByApplication($status)
    {
        return self::FILTER_STATUS[$status] ?? [];
    }

    public function getStatusesByService($status)
    {
        return self::FILTER_SERVICE_STATUS[$status] ?? [];
    }

    public function getStatuses($application_status, $service_status)
    {
        $application_statuses = $this->getStatusesByApplication($application_status);
        $service_statuses = $this->getStatusesByService($service_status);
        $statues = array_values(array_intersect($application_statuses, $service_statuses));
        $statues[] = $service_status;
        $statues = ApplicationServiceStatus::where('type', 'service')->whereIn('status_value', $statues)->get();
        return $statues;
    }
}
