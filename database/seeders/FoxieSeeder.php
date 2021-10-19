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
            $agency =  Agency::create(["name" => "Foxie-Hood-Agent" , "type" => Agency::TYPE_FRANCHISED]);
            $agency->offices()->create([ 
                'name' => "Enkaizen",
                'street_address' => "Baridhara",
                'city' => "Dhaka",
                'state' => "Mahakhali",
                'postcode' => "4000",
                'country' => "Bangladesh",
                'abn' => "2",
                'phone' => "01714556987",
                'email' => "admin@enkaizen.com"
            ]);
        }
    }
}
