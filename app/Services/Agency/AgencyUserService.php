<?php


namespace App\Services\Agency;


use App\Models\AgentProfile;
use App\Models\User;

class AgencyUserService
{

    /**
     * @return User
     */
    public function getUserByProfile($id): User
    {
        $agentProfile = AgentProfile::find($id);
        if($agentProfile)
        {
            return $agentProfile->user;
        }
    }
}
