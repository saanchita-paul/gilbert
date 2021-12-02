<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class PropertyMeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count =  Agency::where('name' , "PropertyMe-Hood-Agency" )->count();
        if($count < 1){
            $agency =  Agency::create(["name" => "PropertyMe-Hood-Agency" , "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "PropertyMe-Hood-Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "041714556987",
                'email' => "propertyme@hood.ai"
            ]);
        }
    }
}
