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
        return UserInvitation::where('token_code', $token)
            ->where('status', 'pending')
            ->whereDate('valid_till', '>', Carbon::now())
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

    public function updatePassword($request)
    {
        $user = User::where('email' , $request['email']);
        if($user->first())
        {
            return $user->update(['password'=> Hash::make($request['password'])]);
        }
    }
}
