<?php


namespace App\Services\Agency;


use App\Mail\InviteUserMail;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\User;
use App\Models\UserInvitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class CreateAgentAndUser
{
    public function createAgent( array $agentData)
    {
        /** @var AgentProfile $agent */
        $agent = AgentProfile::create($agentData);

//        Mail::to($agentData['email'])->send(new InviteUserMail($agent));
        return $agent;
    }

    public function createUser(array $userData)
    {
        /** @var User $user */
        $user = User::create(array_merge($userData, ['profile_type' => 'App\Models\AgentProfile']));
        $user->assignRole($userData['role']);
        return $user;

    }

    public function createUserInvitation(array $userData)
    {
        $userInvitation = new UserInvitation();
        $userInvitation->token_code = $this->generateToken();
        $userInvitation->email = $userData['email'];
        $userInvitation->user_id = $userData['id'];
        $userInvitation->valid_till = Carbon::now()->addHour(72)->format('Y-m-d H:i:s');

        return $userInvitation->save();
    }

    public function createAgentAndUser(array $inputData)
    {
        $office =  Office::query()->find($inputData['office_id']);
        $inputData['agency_id'] = $office->agency_id;
        $agent = $this->createAgent($inputData);
        $inputData['profile_id'] = $agent->id;
        $user = $this->createUser($inputData);
        return $agent;
    }

    protected function generateToken()
    {
        return Str::random(30);
    }

}
