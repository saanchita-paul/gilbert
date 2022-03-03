<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\RolePermissionMigrationService;
use App\Services\CreateApplicationMigrationService;


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
    }
}
