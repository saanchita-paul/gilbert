<?php


namespace App\Services\Agency;


use App\Mail\InviteUserMail;
use App\Models\AgentProfile;
use App\Models\Office;
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
        $user = User::create(array_merge($userData, ['profile_type' => 'App\Models\AgentProfile']));
        $user->assignRole($userData['role']);
        return $user;

    }

    public function createAgentAndUser(array $inputData)
    {
        $office =  Office::query()->find($inputData['office_id']);
        $inputData['agency_id'] = $office->agency_id;
        $agent = $this->createAgent($inputData);
        $inputData['profile_id'] = $agent->id;
        $user = $this->createUser($inputData);
        return $agent;
    }

}
