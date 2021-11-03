<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class IgniteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count =  Agency::where('name' , "Ignite-Hood-Agency" )->count();
        if($count < 1){
            $agency =  Agency::create(["name" => "Ignite-Hood-Agency" , "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "Ignite-Hood-Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "041714556987",
                'email' => "ignite@hood.ai"
            ]);
        }
    }
}
