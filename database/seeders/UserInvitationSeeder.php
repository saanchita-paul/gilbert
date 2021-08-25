<?php

namespace Database\Seeders;

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
        $this.$this->createUser();
        UserInvitation::factory()
            ->count(1)
            ->create();
    }

    private function createUser(){
        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = 'sarwar.sikder@enkaizen.com';
        $user->profile_type = User::PROFILE_TYPE_AGENT;
        $user->save();

    }
}
