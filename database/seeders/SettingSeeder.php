<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'setting_key' => 'auto_assign_to_chatbot',
                'setting_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        Setting::insert($settings);
        $this->command->info('Settings table seeded!');
    }
}
