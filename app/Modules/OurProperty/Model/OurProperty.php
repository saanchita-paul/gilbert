<?php


namespace App\Modules\OurProperty\Model;


use Illuminate\Database\Eloquent\Model;

class OurProperty extends Model
{
    protected $table = 'our_property';

    protected $fillable = [
        'all_fields_dump',
        'connection_application_id',
        'lead_id',
        'agency_name',
        'agent_name',
        'agent_email'
    ];
}
