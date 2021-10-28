<?php


namespace App\Services\Agency;


use App\Models\AgentProfile;
use App\Models\User;

class AgencyUserService
{




    private function updateMail($request, AgentProfile $agentProfile)
    {
        $email = $request['email'];

        if($email !== $agentProfile->user->email)
        {
            $user = $agentProfile->user;
            $user->email = $email;
            $user->save();
        }
    }


    /**
     * @return User
     */
    public function getUserByProfile($request, $id): User
    {
        $agentProfile = AgentProfile::find($id);
        $this->updateMail($request, $agentProfile);
        if($agentProfile)
        {
            return $agentProfile->user;
        }
    }
}
