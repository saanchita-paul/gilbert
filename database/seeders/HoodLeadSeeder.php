<?php

namespace Database\Seeders;

use App\Models\Agency;
use HoodLead\HoodLead;
use Illuminate\Database\Seeder;

class HoodLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count =  Agency::where('name' , "HoodAI-Agency" )->count();
        if($count < 1){
            $agency =  Agency::create(["name" => "HoodAI-Agency" , "type" => Agency::TYPE_INDEPENDENT]);
            $agency->offices()->create([
                'name' => HoodLead::DEFAULT_OFFICE,
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "041714556987",
                'email' => "lead@hood.ai"
            ]);
        }
    }
}
