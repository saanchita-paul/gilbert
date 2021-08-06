<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function offices()
    {
        return $this->hasMany(Office::class);
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
    public function connectionApplications()
    {
        return $this->hasMany(ConnectionApplication::class);
    }
}
