<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationServiceStatus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Types
    const TYPE_APPLICATION = 'application';
    const TYPE_SERVICE = 'service';
    public static $types = [
        self::TYPE_APPLICATION,
        self::TYPE_SERVICE,
    ];

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
        self::STATUS_ASSIGNED=>'assigned',
        self::STATUS_ESCALATED => 'escalated',
        self::STATUS_SUBMITTED => 'submitted',
        self::STATUS_ACCEPTED =>'accepted',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_EA_PROCESSING => 'processing',
        self::STATUS_ENERGY_SUBMIT => 'processing',
        self::STATUS_CLOSED => 'closed',
        self::STATUS_CANT_CONNECT => "can't_connect",
        self::STATUS_NEEDS_MORE_INFO => 'need_more_info',
        self::AC_MANUAL_PROCESSING => 'ac_manual_precessing',
        self::STATUS_FAILED => 'failed',
    ];
}
