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

        $this->createAdmin();
    }

    /**
     *
     */
    private function deleteAdminIfExist()
    {
        User::query()->where('email', 'admin@hood.ai')->delete();
    }

    /**
     *
     */
    private function createAdmin()
    {
        $profile = new HoodProfile();
        $profile->first_name = 'Hood';
        $profile->last_name = 'Admin';
        $profile->save();

        $user = new User();
        $user->password = bcrypt('123456');
        $user->email = 'admin@hood.ai';
        $user->profile_type = User::PROFILE_TYPE_HOOD;
        $user->profile_id = $profile->id;
        $user->save();
        $user->assignRole(RolePermission::ROLE_HOOD_ADMIN);
    }
}
