<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MriAgent;
use App\Models\Office;
use App\Models\AgentProfile;
use Illuminate\Database\Seeder;
use App\Models\MriApplication;
use App\Models\MriProperty;

class MRITestDataMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testOffice = Office::where('name', 'MRI Hood Office')->firstOrFail();
        
        $testAgentProfile = AgentProfile::factory()->make();
        $testAgentProfile->office_id = $testOffice->id;
        $testAgentProfile->agency_id = $testOffice->agency_id;
        $testAgentProfile->save();
        
        $testMriAgent = MriAgent::factory()->create();
        
        $testUser = User::factory()->make();
        $testUser->email = $testMriAgent->email_address;
        $testUser->profile_type = AgentProfile::class;
        $testUser->profile_id = $testAgentProfile->id;
        $testUser->save();
        
        $testMriApplication = MriApplication::factory()->withAuthorizedPerson()->create();
        $testMriProperty = MriProperty::factory()->make();
        $testMriApplication->mriProperty()->save($testMriProperty);
        $testMriProperty = $testMriApplication->mriProperty;
        $testMriProperty->agents = $testMriAgent->agent_id;
        $testMriProperty->mriAgents()->sync($testMriAgent);
    }
}
