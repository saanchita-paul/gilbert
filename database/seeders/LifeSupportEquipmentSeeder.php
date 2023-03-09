<?php

namespace Database\Seeders;

use App\Models\LifeSupportEquipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LifeSupportEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lifeSupportEquipments = [
            [
                'name' => 'Oxygen Concentrator',
                'powershop_value' => 'Oxygen Concentrator',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Intermittent Peritoneal Dialysis Machine',
                'powershop_value' => 'Intermittent Peritoneal Dialysis Machine',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Kidney Dialysis Machine',
                'powershop_value' => 'Kidney Dialysis Machine',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Chronic Positive Airways Pressure Respirator',
                'powershop_value' => 'Chronic Positive Airways Pressure Respirator',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Crigler Najjar Syndrome Phototherapy Equipment',
                'powershop_value' => 'Crigler Najjar Syndrome Phototherapy Equipment',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Ventilator For Life Support',
                'powershop_value' => 'Ventilator For Life Support',
                'created_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'powershop_value' => 'Other',
                'created_at' => now(),
                'is_active' => true,
            ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LifeSupportEquipment::query()->truncate();
        LifeSupportEquipment::query()->insert($lifeSupportEquipments);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Life support equipment table seeded!');
    }
}
