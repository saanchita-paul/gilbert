<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(DefwithCredentialsaultSeeder::class);
        $this->call(PermissionMigrationSeeder::class);
        $this->call(ApplicationCloseReasonSeeder::class);
        $this->call(MRISeeder::class);
        $this->call(ApplicationServiceStatusSeeder::class);
    }
}
