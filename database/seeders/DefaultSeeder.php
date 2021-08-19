<?php

namespace Database\Seeders;

use App\Models\HoodProfile;
use App\Models\User;
use App\Services\RolePermission;
use Illuminate\Database\Seeder;

/**
 *
 */
class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->deleteAdminIfExist();

        $this->createAdmin('admin@hood.ai', 'Hood', 'Admin', RolePermission::ROLE_HOOD_ADMIN);
        $this->createAdmin('nick@hood-agent.com', 'Hood', 'Agent', RolePermission::ROLE_HOOD_AGENT);
    }

    /**
     *
     */
    private function deleteAdminIfExist()
    {
        User::query()->where('email', 'admin@hood.ai')->delete();
        User::query()->where('email', 'nick@hood-agent.com')->delete();
    }

    /**
     *
     */
    private function createAdmin($email, $fName, $lName, $role)
    {
        $profile = new HoodProfile();
        $profile->first_name = $fName;
        $profile->last_name = $lName;
        $profile->save();

        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = $email;
        $user->profile_type = User::PROFILE_TYPE_HOOD;
        $user->profile_id = $profile->id;
        $user->save();
        $user->assignRole($role);
    }
}
