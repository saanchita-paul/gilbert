<?php


namespace App\Services;

use App\Models\AgentProfile;
use Exception;
use App\Models\User;

class UpdateUserProfileService
{
    private $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function updateProfile($profileData)
    {
        try {
            $user = User::findOrFail($this->id);
            $user->profile->update($profileData);
            $user->update(['email'=>$profileData['email']]);
            return $user->refresh();
        } catch (\Throwable $th) {
            throw new Exception("Error Processing Request", 1);
        }
    }
    
    public function updateUserData($profileData){
        try {
            $agent = AgentProfile::findOrFail($this->id);
            $user = $agent->user;
            $agent->update( $profileData );
            $user->update( ['email' => $profileData['email']] );
            $roles = [ $profileData['role'] ];
            $this->updateRole($roles,  $user);
            return $user->refresh();
        } catch (\Throwable $th) {
            throw new Exception("Error Processing Request", 1);
        }
    }
    
    private function updateRole($roles, $user){
            $user->syncRoles($roles);
    }
}
