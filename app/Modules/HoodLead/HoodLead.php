<?php

namespace HoodLead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoodLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'all_fields_dump',
        'connection_application_id',
        'lead_id'
    ];

    const DEFAULT_OFFICE = "HoodAI-Office";
}
