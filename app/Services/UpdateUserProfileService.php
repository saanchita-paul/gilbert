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

}
