<?php


namespace App\Services;


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
        $user = User::findOrFail($this->id);
        $user->profile->update($profileData);
        $user->update(['email'=>$profileData['email']]);
        return $user->refresh();
    }
    
    public function updateUserData($profileData){
        $user = $this->updateProfile($profileData);
        $this->updateRole($profileData['role'],  $user);
        return $user->refresh();
    }
    
    private function updateRole($roles, $user){
        $user->syncPermissions($roles);
    }
}
