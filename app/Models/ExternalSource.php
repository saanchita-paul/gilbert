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

    public function defaultOffice()
    {
        return $this->belongsTo(Office::class, 'default_office_id');
    }

    public function connectionApplications()
    {
        return $this->hasMany('ConnectionApplication', 'external_source_id');
    }

    public function getDisplayTypeNameAttribute($value)
    {
        if (empty($value)) {
            return ucfirst($this->source_type);
        }

        return $value;
    }
}
