<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\MRI\MriAgentFactory;

class MriAgent extends Model
{
    use HasFactory;

    public const ROLE_PROPERTY_MANAGER = 'Property Manager';
    public const ROLE_INSPECTING_AGENT = 'Inspecting Agent';
    public const ROLE_LEASING_AGENT = 'Leasing Agent';

    protected $guarded = ['id'];

    /**
     * Get the agent profile associated with the mri agent.
     */
    public function agentProfile()
    {
        return $this->belongsTo(AgentProfile::class);
    }

    public function mriProperties()
    {
        return $this->belongsToMany(MriProperty::class, 'mri_agent_properties');
    }

    protected static function newFactory()
    {
        return MriAgentFactory::new();
    }

    public function getAgentNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRolesListAttribute()
    {
        return explode(',', $this->roles);
    }

}
