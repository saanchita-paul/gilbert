<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AustralianHoliday extends Model
{
    protected $table = 'australian_holidays';
    public $timestamps = false;
    protected $fillable = [
        'date',
        'name',
        'jurisdiction',
    ];

}
