<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AgentProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'office_id',
        'agency_id',
        'first_name',
        'last_name',
        'f_id_12',
//        'email',
        'phone',
        'profile_photo'
    ];

    /**
     * @return MorphOne
     */
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    /**
     * @return BelongsTo
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

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
    public function createdApplications()
    {
        return $this->hasMany(ConnectionApplication::class, 'created_by');
    }

    /**
     * @return HasMany
     */
    public function assignedApplications()
    {
        return $this->hasMany(ConnectionApplication::class, 'assigned_to');
    }
}
