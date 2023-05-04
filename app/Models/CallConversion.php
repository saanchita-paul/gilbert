<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_application_id',
        'caller_id',
        'conversation_at',
        'call_start_at',
        'call_end_at',
        'uploaded_at',
        'reason',
        'status',
    ];


    public const STATUS_FETCHED = 'fetched';
    public const STATUS_FETCH_FAILED = 'fetch_failed';
    public const STATUS_UPLOADED = 'uploaded';
    public const STATUS_UPLOAD_FAILED = 'upload_failed';

    /**
     * @return BelongsTo
     */
    public function connectionApplication(): BelongsTo
    {
        return $this->belongsTo(ConnectionApplication::class, 'connection_application_id');
    }
}
