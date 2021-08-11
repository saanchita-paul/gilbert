<?php

namespace Database\Seeders;

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
        $user = new User();
        $user->name = 'Admin';
        $user->password = bcrypt('123456');
        $user->email = 'admin@hood.ai';
        $user->save();
        $user->assignRole(RolePermission::ROLE_HOOD_ADMIN);
    }
}
