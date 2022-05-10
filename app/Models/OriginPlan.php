<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginPlan extends Model
{
    use HasFactory;

    protected $fillable = ['product_code', 'campaign_id'];
}
