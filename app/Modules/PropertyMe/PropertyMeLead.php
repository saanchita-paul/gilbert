<?php

namespace PropertyMe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyMeLead extends Model
{
    use HasFactory;

    protected $table = "property_me_leads";

    protected $guarded = ["id"];

    public $timestamps;
}
