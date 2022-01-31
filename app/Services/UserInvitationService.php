<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInvitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserInvitationService
{
    public function getInvitationByToken(array $data)
    {
        $token = $data['token'];
        return UserInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('valid_till', '>', Carbon::now())
            ->with('user.profile')
            ->first();
    }

    public function createUserInvitation($inputData)
    {
        $userInvitation = new UserInvitation();
        $userInvitation->email = $inputData['email'];
        $userInvitation->user_id = $inputData['user_id'];
        $userInvitation->valid_till = Carbon::now()->addHour(72)->format('Y-m-d H:i:s');
        return UserInvitation::create($userInvitation);
    }

    public function updatePassword($updateData)
    {
        $userInvitation = UserInvitation::where('token' , $updateData['token'])
            ->first();
        $user = User::find($userInvitation->user_id);
        if($user->first())
        {
            return $user->update(['password'=> Hash::make($updateData['password'])]);
        }
    }

    public function updateToken($updateData)
    {
        $userInvitation = UserInvitation::where('token' , $updateData['token']);
        if($userInvitation->first())
        {
            return $userInvitation->update(['status'=> 'successful']);
        }
    }

    public function setEmailVerificationTime($updateData){
        $userInvitation = UserInvitation::where('token' , $updateData['token'])->first();
        $user = User::where('email' , $userInvitation->email);
        if($user->first())
        {
            return $user->update(['email_verified_at'=> now()]);
        }
    }
}
