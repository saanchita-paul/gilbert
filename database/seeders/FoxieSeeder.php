<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class FoxieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count =  Agency::where('name' , "Foxie-Hood-Agent" )->count();
        if($count < 1){
            $agency =  Agency::create(["name" => "Foxie-Hood-Agent" , "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "Foxie Office",
                'street_address' => "100 plenty road",
                'city' => "a",
                'state' => "VIC",
                'postcode' => "3083",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "041714556987",
                'email' => "foxie@hood.ai"
            ]);
        }
    }
}
