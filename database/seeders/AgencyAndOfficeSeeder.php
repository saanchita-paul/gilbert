<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Office;
use Illuminate\Database\Seeder;

class AgencyAndOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Agency::factory()
            ->count(50)
            ->has(Office::factory()->count(3), 'offices')
            ->create();
    }
}
