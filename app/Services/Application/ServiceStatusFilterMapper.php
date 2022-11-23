<?php

namespace App\Services\Application;

use App\Models\ConnectionService;

class ServiceStatusFilterMapper
{
    // Application statuses
    public const STATUS_UNASSIGNED = 1;
    public const STATUS_ASSIGNED = 2;
    public const STATUS_ESCALATED = 3;
    public const STATUS_SUBMITTED = 4;
    public const STATUS_ACCEPTED = 5;
    public const STATUS_REJECTED = 6;
    public const STATUS_EA_PROCESSING = 7;
    public const STATUS_CLOSED = 8;
    public static $applicationStatusMap = [
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
    public const STATUS_CANT_CONNECT = 9;
    public const STATUS_NEEDS_MORE_INFO = 10;
    public const AC_MANUAL_PROCESSING = 11;
    public const STATUS_ENERGY_SUBMIT = 12;
    public const STATUS_FAILED = 13;
    public static $serviceStatusMap = [
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
    public const FILTER_STATUS = [
        self::STATUS_UNASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ESCALATED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_SUBMITTED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_CLOSED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
    ];

    public const WATER_STATUSES = [
        self::STATUS_UNASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ESCALATED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_SUBMITTED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_CLOSED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
    ];

    public const INTERNET_STATUSES = [
        self::STATUS_UNASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ASSIGNED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_ESCALATED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_SUBMITTED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
        self::STATUS_CLOSED => [
            self::STATUS_ASSIGNED => [
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_ESCALATED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_SUBMITTED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
            self::STATUS_CLOSED => [
                self::STATUS_ENERGY_SUBMIT,
                self::STATUS_ACCEPTED,
                self::STATUS_CANT_CONNECT,
                self::AC_MANUAL_PROCESSING,
                self::STATUS_EA_PROCESSING
            ],
        ],
    ];


    public const PROVIDERPLANALLOWABLESTATUSES = [
        self::STATUS_ESCALATED,
        self::STATUS_SUBMITTED,
        self::STATUS_ENERGY_SUBMIT,
        self::AC_MANUAL_PROCESSING,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
        self::STATUS_CANT_CONNECT,
        self::STATUS_CLOSED
    ];

    private function getStatusesByApplication($oldStatus, $newStatus)
    {
        return self::FILTER_STATUS[$oldStatus][$newStatus] ?? [];
    }

    public function getStatuses($newAppStatus, $service_id)
    {
        $connectionService = $this->getConnectionService($service_id);
        $hasProviderPlan = $this->getHasProviderPlan($service_id);
        $statuses = $this->getStatusesByApplication(
            $connectionService->connectionApplication->status,
            $newAppStatus
        );
        if (
            $this->isProviderPlanAllowableStatus($newAppStatus) &&
            (!$hasProviderPlan['has_provider'] || !$hasProviderPlan['has_plan'])
        ) {
            $statuses = [($this->getConnectionService($service_id))->status];
        }

        return $statuses;
    }

    public function getWaterServiceStatuses($oldAppStatus, $newAppStatus)
    {
        return self::WATER_STATUSES[$oldAppStatus][$newAppStatus] ?? [];
    }

    public function getInternetServiceStatuses($oldAppStatus, $newAppStatus)
    {
        return self::INTERNET_STATUSES[$oldAppStatus][$newAppStatus] ?? [];
    }

    private function isProviderPlanAllowableStatus($status)
    {
        return in_array($status, self::PROVIDERPLANALLOWABLESTATUSES);
    }

    private function getHasProviderPlan($service_id)
    {
        $service = $this->getConnectionService($service_id);
        return [
            'has_provider' => (bool)$service->provider_name,
            'has_plan' => (bool)$service->plan_type
        ];
    }

    private function getConnectionService($service_id)
    {
        return ConnectionService::find($service_id);
    }
}
