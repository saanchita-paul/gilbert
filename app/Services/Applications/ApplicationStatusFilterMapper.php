<?php

namespace App\Services\Applications;

use App\Models\ConnectionApplication;

/**
 *
 */
class ApplicationStatusFilterMapper
{
    const MY_APPLICATION = 'my_applications';
    const UNASSIGNED = 'unassigned';
    const ASSIGNED = 'assigned';
    const CLOSED = 'closed';
    const ESCALATED = 'escalated';
    const SUBMITTED = 'submitted';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';
    const IN_PROGRESS = 'in_progress';
    const PROCESSING = 'processing';
    /**
     *
     */
    const FILTER_TO_STATUSES = [
        self::MY_APPLICATION => [
            ConnectionApplication::STATUS_ASSIGNED,
            #todo: need to add submission-failed status here
        ],
        self::UNASSIGNED => [
            ConnectionApplication::STATUS_UNASSIGNED
        ],
        self::ASSIGNED => [
            ConnectionApplication::STATUS_ASSIGNED
        ],
        self::ESCALATED => [
            ConnectionApplication::STATUS_ESCALATED
        ],
        self::SUBMITTED => [
            ConnectionApplication::STATUS_SUBMITTED,
            ConnectionApplication::STATUS_REJECTED,
            ConnectionApplication::STATUS_ACCEPTED,
            ConnectionApplication::STATUS_EA_PROCESSINF, #todo: need to check this status,
        ],
        self::ACCEPTED => [
            ConnectionApplication::STATUS_ACCEPTED
        ],
        self::REJECTED => [
            ConnectionApplication::STATUS_REJECTED
        ],
        self::IN_PROGRESS => [
            ConnectionApplication::STATUS_UNASSIGNED,
            ConnectionApplication::STATUS_ASSIGNED,
            ConnectionApplication::STATUS_ESCALATED,
            ConnectionApplication::STATUS_SUBMITTED
        ],
        self::PROCESSING => [
            ConnectionApplication::STATUS_EA_PROCESSINF
        ],
        self::CLOSED => [
            ConnectionApplication::STATUS_CLOSED
        ],
    ];


    /**
     * @param string $status
     * @return array
     */
    public static function getStatuses(string $status): array
    {
        return self::FILTER_TO_STATUSES[$status] ?? [];
    }

    /**
     * @param int|null $status
     * @return string|null
     */
    public static function getFilter(?int $status): ?string
    {
        return match ($status) {
            ConnectionApplication::STATUS_ASSIGNED => self::ASSIGNED,
            ConnectionApplication::STATUS_UNASSIGNED => self::UNASSIGNED,
            ConnectionApplication::STATUS_ESCALATED => self::ESCALATED,
            ConnectionApplication::STATUS_ACCEPTED,
            ConnectionApplication::STATUS_REJECTED,
            ConnectionApplication::STATUS_SUBMITTED,
            ConnectionApplication::STATUS_EA_PROCESSINF => self::SUBMITTED,
            ConnectionApplication::STATUS_CLOSED => self::CLOSED,
            default => null
        };
    }
}
