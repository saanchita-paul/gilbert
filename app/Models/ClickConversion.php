<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickConversion extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public const IS_UPLOADED_CLICK_FALSE = 0;
    public const IS_UPLOADED_CLICK_TRUE = 1;

    public function connectionApplication(): BelongsTo {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
