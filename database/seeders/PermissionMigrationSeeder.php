<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\RolePermissionMigrationService;
use App\Services\CreateApplicationMigrationService;
use App\Services\AddPermissionToCsrRoleService;


class PermissionMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        (new RolePermissionMigrationService())->setPermissions();
        (new CreateApplicationMigrationService())->setPermissions();
        (new AddPermissionToCsrRoleService())->setPermissions();
    }
}
