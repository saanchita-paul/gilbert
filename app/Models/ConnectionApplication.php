<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConnectionApplication extends Model
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
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(AgentProfile::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function assignedTo()
    {
        return $this->belongsTo(AgentProfile::class, 'assigned_to');
    }

    /**
     * @return HasMany
     */
    public function connectionServices()
    {
        return $this->hasMany(ConnectionService::class);
    }

    /**
     * @return HasOne
     */
    public function identification()
    {
        return $this->hasOne(Identification::class);
    }

    /**
     * @return HasMany
     */
    public function applicationNotes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}
