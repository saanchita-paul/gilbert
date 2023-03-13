<?php

namespace Database\Seeders;

use App\Models\Hazard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HazardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hazards = [
            [
                'title' => "Dog on the property",
                'powershop_value' => "dog",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Electric Fence",
                'powershop_value' => "electric_fence",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Cautionary objects",
                'powershop_value' => "caution",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Electrical safety issue",
                'powershop_value' => "electrical_safety_issue",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Asbestos board",
                'powershop_value' => "asbestos_board",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Asbestos fuse",
                'powershop_value' => "asbestos_fuse",
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'title' => "Others (Specify)",
                'powershop_value' => "other",
                'is_active' => true,
                'created_at' => now(),
            ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Hazard::query()->truncate();
        Hazard::query()->insert($hazards);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Hazards table seeded!');
    }
}
