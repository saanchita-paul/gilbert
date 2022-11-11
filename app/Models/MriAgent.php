<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriAgent extends Model
{
    use HasFactory;

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

}
