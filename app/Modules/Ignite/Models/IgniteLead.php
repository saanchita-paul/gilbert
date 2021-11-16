<?php

namespace Ignite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IgniteLead extends Model
{
    use HasFactory;
    protected $table = "ignite_leads";

    protected $guarded = ["id"];

    public $timestamps;

}
