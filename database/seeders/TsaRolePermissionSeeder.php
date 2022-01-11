<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TSARolePermissionService;

class TsaRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        (new TSARolePermissionService())->seed();
    }
}
