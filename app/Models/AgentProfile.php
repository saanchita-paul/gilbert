<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\AgentProfile
 *
 * @property int $id
 * @property int $office_id
 * @property int $agency_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $f_id_12
 * @property string|null $phone
 * @property string|null $profile_photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency $agency
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectionApplication[] $assignedApplications
 * @property-read int|null $assigned_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectionApplication[] $createdApplications
 * @property-read int|null $created_applications_count
 * @property-read \App\Models\Office $office
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AgentProfileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereFId12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgentProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
