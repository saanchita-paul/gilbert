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
    public static $status_mapping = [
        'unassigned' => self::STATUS_UNASSIGNED,
        'assigned' => self::STATUS_ASSIGNED,
        'escalated' => self::STATUS_ESCALATED,
        'submitted' => self::STATUS_SUBMITTED,
        'accepted' => self::STATUS_ACCEPTED,
        'rejected' => self::STATUS_REJECTED,
        'processing' => self::STATUS_EA_PROCESSING,
        'closed' => self::STATUS_CLOSED,
    ];
    public static $status_values = [
        self::STATUS_UNASSIGNED => 'unassigned',
        self::STATUS_ASSIGNED => 'assigned',
        self::STATUS_ESCALATED => 'escalated',
        self::STATUS_SUBMITTED => 'submitted',
        self::STATUS_ACCEPTED => 'accepted',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_EA_PROCESSING => 'processing',
        self::STATUS_CLOSED => 'closed',
    ];
}
