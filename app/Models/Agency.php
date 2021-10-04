<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Agency
 *
 * @property int $id
 * @property int $type
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgentProfile[] $agentProfiles
 * @property-read int|null $agent_profiles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectionApplication[] $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $offices
 * @property-read int|null $offices_count
 * @method static \Database\Factories\AgencyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Agency extends Model
{
    use HasFactory;

    const TYPE_INDEPENDENT = 0;
    const TYPE_FRANCHISED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name'
    ];

    /**
     * @return HasMany
     */
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    /**
     * @return HasMany
     */
    public function agentProfiles(): HasMany
    {
        return $this->hasMany(AgentProfile::class);
    }

    /**
     * @return HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ConnectionApplication::class);
    }
}
