<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickConversion extends Model
{
    use HasFactory;
    protected $fillable = [
        'gcl_id',
        'connection_application_id',
        'conversion_date',
        'last_checked',
        'should_skip',
        'uploaded_at',
        'status',
        'reason',
        'generated_from_creation',
    ];

    public const IS_UPLOADED_CLICK_FALSE = 0;
    public const IS_UPLOADED_CLICK_TRUE = 1;

    public const GENERATED_FROM_CREATION_FALSE = 0;

    public function connectionApplication(): BelongsTo {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
