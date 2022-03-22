<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class TAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count =  Agency::where('name' , "tApp-Agency" )->count();
        if($count < 1){
            $agency =  Agency::create(["name" => "tApp-Agency" , "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => "TApp-Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "6",
                'phone' => "0417155345",
                'email' => "tapp@hood.ai"
            ]);
        }
    }
}
