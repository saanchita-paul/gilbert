<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class MRISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count =  Agency::where('name' , "MRI Hood Agency" )->count();
        if($count < 1) {
            $agency =  Agency::create(["name" => "MRI Hood Agency", "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "MRI Hood Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "0417145569",
                'email' => "mri@hood.ai"
            ]);
        }
    }
}
