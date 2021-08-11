<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\ConnectionApplication;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ConnectionApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConnectionApplication::factory()
            ->count(10)
            ->state(new Sequence(
                fn($sequence) => ['office_id' => Office::all()->random()],
                fn($sequence) => ['agency_id' => Agency::all()->random()],
            ))
            ->create();
    }
}
