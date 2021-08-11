<?php


namespace App\Services\Agency;


use App\Mail\InviteUserMail;
use App\Models\AgentProfile;
use App\Models\User;

class CreateAgentAndUser
{
    public function createAgent( array $agentData)
    {
        /** @var AgentProfile $agent */
        $agent = AgentProfile::create($agentData);
//        \Mail::to($agent->email)->send(new InviteUserMail());
        return $agent;
    }

    public function createUser(array $userData)
    {
        /** @var User $user */
        $user = User::create($userData);
        $user->assignRole($userData['role']);
        return $user;

    }
}
