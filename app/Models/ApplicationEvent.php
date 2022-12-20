<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationEvent extends Model
{
    use HasFactory;

    const TWIDDLE_SMS_CLICK = 'Twiddle SMS Click';
    const EVENT_TYPE = [
        'twiddle_sms_click' => self::TWIDDLE_SMS_CLICK
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'event_type',
    ];
}
