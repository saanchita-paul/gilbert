<?php

namespace Database\Seeders;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ConnectionServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConnectionService::factory()
            ->count(50)
            ->state(new Sequence(
                fn($sequence) => ['connection_application_id' => ConnectionApplication::all()->random()],
            ))
            ->create();
    }
}
