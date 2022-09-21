<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppCloseReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'is_inactive'
    ];

    public function disable()
    {
        $this->is_inactive = true;
        $this->save();
    }

    public function enable()
    {
        $this->is_inactive = false;
        $this->save();
    }
}
