<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentProfile extends Model
{
    use HasFactory;

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
