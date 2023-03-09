<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hazard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function connectionApplications(): BelongsToMany
    {
        return $this->belongsToMany(
            ConnectionApplication::class,
            'hazard_connection_applications',
            'hazard_id',
            'connection_application_id'
        )->withTimestamps();
    }
}
