<?php

namespace App\Models;

use Database\Factories\MRI\MriPropertyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriProperty extends Model
{
    use HasFactory;

    public function application()
    {
        return $this->belongsTo(MriApplication::class);
    }

    public function mriAgents()
    {
        return $this->belongsToMany(MriAgent::class, 'mri_agent_properties');
    }

    protected static function newFactory()
    {
        return MriPropertyFactory::new();
    }

    public function mriLog()
    {
        return $this->belongsTo(MriLog::class);
    }
}
