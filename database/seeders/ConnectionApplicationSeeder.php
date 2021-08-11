<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgentProfile;
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
            ->count(50)
            ->state(new Sequence(
                fn($sequence) => ['office_id' => Office::all()->random()],
            ))
            ->state(new Sequence(
                fn($sequence) => ['agency_id' => Agency::all()->random()],
            ))
            ->state(new Sequence(
                fn($sequence) => ['created_by' => AgentProfile::all()->random()],
            ))
            ->state(new Sequence(
                fn($sequence) => ['assigned_to' => AgentProfile::all()->random()],
            ))
            ->create();
    }
}
