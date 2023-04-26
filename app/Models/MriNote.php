<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriNote extends Model
{
    use HasFactory;

    protected $casts = [
        'is_checked' => 'boolean',
        'is_fetched' => 'boolean'
    ];

    public function mriApplication()
    {
        return $this->belongsTo(MriApplication::class);
    }

    public function mriLog()
    {
        return $this->belongsTo(MriLog::class);
    }
}
