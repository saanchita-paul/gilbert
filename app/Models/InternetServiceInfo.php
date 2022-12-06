<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetServiceInfo extends Model
{
    protected $guarded = ['id'];


    public function connectionApplication(): BelongsTo
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
