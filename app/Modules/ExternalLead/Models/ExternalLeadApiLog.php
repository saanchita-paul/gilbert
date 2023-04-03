<?php

namespace ExternalLead\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalLeadApiLog extends Model
{
    protected $table = 'external_lead_api_logs';

    protected $fillable = [
        'all_fields_dump',
        'external_source_id',
        'connection_application_id',
        'lead_id',
        'agency_name',
        'agent_name',
        'agent_email',
        'exception_log'
    ];
}
