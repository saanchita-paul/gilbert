<?php

namespace App\Models\Foxie;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugerLeads extends Model
{
    use HasFactory;
    protected $table = "suger_leads";

    protected $guarded = ["id"];

}
