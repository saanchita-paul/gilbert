<?php

namespace App\Services;

use App\Models\UserInvitation;
use phpDocumentor\Reflection\Utils;

class UserInvitationService
{
    public function getInvitationByToken(array $data){
        $token = $data['token'];
        return UserInvitation::where('token_code', $token)->first();
    }
}
