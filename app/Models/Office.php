<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agency_id',
        'name',
        'street_address',
        'city',
        'state',
        'postcode',
        'country',
        'abn',
        'phone',
        'email'
    ];

    /**
     * @return BelongsTo
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * @return HasMany
     */
    public function agentProfiles()
    {
        return $this->hasMany(AgentProfile::class);
    }

    /**
     * @return HasMany
     */
    public function officeCommissions()
    {
        return $this->hasMany(OfficeCommission::class);
    }

    /**
     * @return HasMany
     */
    public function connectionApplications()
    {
        return $this->hasMany(ConnectionApplication::class);
    }
}
