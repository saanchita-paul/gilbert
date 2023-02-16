<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSource extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $guarded = [];

    public function defaultOffice()
    {
        return $this->belongsTo(Office::class, 'id', 'default_office_id');
    }
}
