<?php

namespace Database\Seeders;

use App\Models\ConnectionApplication;
use App\Models\Office;
use App\Models\AgentProfile;
use App\Models\User;
use App\Services\RolePermission;
use Illuminate\Database\Seeder;
use App\Models\ConnectionService;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAgentProfile();
        $this->createAgentTeamLeaderProfile();
        ConnectionApplication::factory()
            ->count(20)
            ->has(ConnectionService::factory()->count(1), 'connectionServices')
            ->create();
    }

    private function createAgentProfile() {
        $office = Office::first();
        $profile = new AgentProfile();
        $profile->office_id = $office->id;
        $profile->agency_id = $office->agency->id;
        $profile->first_name = 'Hood';
        $profile->last_name = 'Agent';
        $profile->{'f_id_12'} = '123';
        $profile->phone = '1234567890';
        $profile->save();

        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = 'agent@hood.ai';
        $user->profile_type = User::PROFILE_TYPE_AGENT;
        $user->profile_id = $profile->id;
        $user->save();
        $user->assignRole(RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT);
    }

    private function createAgentTeamLeaderProfile() {
        $office = Office::first();
        $profile = new AgentProfile();
        $profile->office_id = $office->id;
        $profile->agency_id = $office->agency->id;
        $profile->first_name = 'Team';
        $profile->last_name = 'Leader';
        $profile->{'f_id_12'} = '123';
        $profile->phone = '1234567890';
        $profile->save();

        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = 'leader@hood.ai';
        $user->profile_type = User::PROFILE_TYPE_AGENT;
        $user->profile_id = $profile->id;
        $user->save();
        $user->assignRole(RolePermission::ROLE_AGENCY_TEAM_LEAD);
    }
}
