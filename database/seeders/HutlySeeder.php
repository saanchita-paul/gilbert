<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agency;

class HutlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agency =  Agency::where('name', "Hutly-Hood Agency")->first();
        if (!$agency) {
            $agency =  Agency::create(["name" => "Hutly-Hood Agency", "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "Hutly-Hood Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "0417145569",
                'email' => "mri@hood.ai",
                'is_default_office' => true,
            ]);
        }
    }
}
