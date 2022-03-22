<?php


namespace ExternalLead\Models;


use Illuminate\Database\Eloquent\Model;

class TApp extends Model
{
    protected $table = 't_app';

    protected $fillable = [
        'all_fields_dump',
        'connection_application_id',
        'lead_id',
        'agency_name',
        'agent_name',
        'agent_email'
    ];
}
