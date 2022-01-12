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
    
    /**
     * Update Agency User
     *
     * @param array $profileData
     *
     * @return User
     */
    public function updateUserData($profileData) : User {
        try {
            $agent = AgentProfile::findOrFail($this->id);
            $user = $agent->user;
            $agent->update( $profileData );
            $user->update([
                'email' => $profileData['email'],
                'is_active' => $profileData['is_active']
            ]);
            $roles = [ $profileData['role'] ];
            $this->updateRole($roles,  $user);
            return $user->refresh();
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), 1);
        }
    }

    /**
     * Update User roles
     *
     * @param array $roles
     *
     * @return bool
     */
    private function updateRole($roles, $user) : bool {
        try {
            $user->syncRoles($roles);
            return true;
        } catch (\Throwable $th) {
                throw new Exception("Problem in assigning role", 1);
        }
    }
}
