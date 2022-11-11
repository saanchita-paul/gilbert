<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriApplication extends Model
{
    use HasFactory;

    public function propertyDetail()
    {
        return $this->hasOne(MriProperty::class);
    }

    /**
     * Get the mri office that owns the mri application.
     */
    public function mriOffice()
    {
        return $this->belongsTo(MriOffice::class);
    }
}
