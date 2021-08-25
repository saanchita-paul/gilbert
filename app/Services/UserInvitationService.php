<?php

namespace App\Services;

use App\Models\UserInvitation;
use phpDocumentor\Reflection\Utils;

class UserInvitationService
{
    public function getInvitationByToken($token){
        $userInvite = UserInvitation::where('token_code',$token['token'])->first();
        dump($userInvite);
        return $userInvite;
    }

    public function updatePassword(){

    }
}
