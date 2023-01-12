<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriNote extends Model
{
    use HasFactory;

    public function mriApplication()
    {
        return $this->belongsTo(MriApplication::class);
    }
}
