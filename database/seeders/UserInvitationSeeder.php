<?php

namespace Database\Seeders;

use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Database\Seeder;

class UserInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUser();
        UserInvitation::factory()
            ->count(1)
            ->create();
    }

    private function createUser(){
        $office = Office::first();
        $profile = new AgentProfile();
        $profile->office_id = $office->id;
        $profile->agency_id = $office->agency->id;
        $profile->first_name = 'Sarwar';
        $profile->last_name = 'Sikder';
        $profile->{'f_id_12'} = '123';
        $profile->phone = '1234567890';
        $profile->save();

        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = 'sarwar.sikder@enkaizen.com';
        $user->profile_id = $profile->id;
        $user->profile_type = User::PROFILE_TYPE_AGENT;
        $user->save();

    }
}
