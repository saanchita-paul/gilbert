<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HazardConnectionApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }

    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
