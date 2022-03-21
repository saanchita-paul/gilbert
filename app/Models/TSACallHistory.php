<?php

namespace App\Models;

use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TSACallHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tsa_call_history';
    protected $fillable = [
        'all_fields_dump',
        'connection_application_id',
        'num_attempts',
        'lead_status',
        'attempts_outcome',
        'attempts_disposition_code',
        'attempts_disposition_sub_code',
        'tsa_id',
        'attempts_id',
        'attempts_assigned_timestamp',
        'attempts_initiated_timestamp',
        'attempts_connected_timestamp',
        'attempts_disconnected_timestamp',
        'attempts_disposed_timestamp',

    ];

    /**
     * @return BelongsTo
     */
    public function ConnectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
