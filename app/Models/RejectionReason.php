<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_service_id',
        'connection_application_id',
        'service_type',
        'reason_code',
        'reason_text'
    ];

    /**
     * service
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(ConnectionService::class, 'connection_service_id');
    }
}
