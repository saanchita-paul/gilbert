<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualStatusChangeLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
    ];

    public function connection_application(): BelongsTo
    {
        return $this->belongsTo(
            ConnectionApplication::class,
            'connection_application_id',
            'id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
