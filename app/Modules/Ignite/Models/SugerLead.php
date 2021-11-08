<?php

namespace Ignite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugerLead extends Model
{
    use HasFactory;
    protected $table = "suger_leads";

    protected $guarded = ["id"];

    public $timestamps;

}
