<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Utils;

class UserInvitationService
{
    public function getInvitationByToken(array $data){
        $token = $data['token'];
        return UserInvitation::where('token_code', $token)->first();
    }

    public function updatePassword($request){
        $user = User::where('email' , $request['email']);
        if($user->first())
        {
            return $user->update(['password'=> Hash::make($request['password'])]);
        }
    }
}
