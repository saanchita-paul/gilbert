<?php

namespace Database\Seeders;

use App\Models\ConnectionApplication;
use App\Models\Identification;
use Illuminate\Database\Seeder;

class IdentificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $application = ConnectionApplication::factory()->create();
        Identification::factory()
            ->count(50)
            ->for($application)
            ->create();
    }
}
