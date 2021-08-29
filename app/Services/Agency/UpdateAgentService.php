<?php


namespace App\Services\Agency;


use App\Models\AgentProfile;

class UpdateAgentService
{
    public function update($data, $id)
    {
        $agent = AgentProfile::findOrFail($id);
        $agent->update($data);
        return $agent->refresh();
    }
}
