<?php

namespace MRI\Services;

use App\Models\MriAgent;
use App\Models\User;
use App\Models\AgentProfile;

class MapAgentService
{
    public function construct()
    {
    }

    public function run()
    {
        $mriAgents = MriAgent::whereNull('agent_profile_id')->get();

        foreach ($mriAgents as $mriAgent) {
            $invalidReason = '';
            $user = User::where('email', $mriAgent->email_address)
                    ->where('profile_type', AgentProfile::class)
                    ->first();

            if (!$user){
                $invalidReason = sprintf('MRI agent is not registered in Gilbert (%s)', $mriAgent->email_address);
            }
            // else if ($user->is_active){
            //     $invalidReason = 'MRI agent is inactive';
            // }
            // else if (empty($user->email_verified_at)){
            //     $invalidReason = 'MRI agent email is not verified';
            // }
            
            if (!empty($invalidReason)) {
                // dump(sprintf($invalidReason));
                \Log::warning($invalidReason);
            }
            else {
                $mriAgent->agent_profile_id = $user->profile_id;
                $mriAgent->save();
                info('Updated MRI Agent profile with ID - ' . $mriAgent->id );
            }
        }
    }
}