<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MriAgent;
use App\Models\Office;
use App\Models\AgentProfile;
use Illuminate\Database\Seeder;
use App\Models\MriApplication;
use App\Models\MriOffice;
use App\Models\MriProperty;
use App\Models\Agency;

class MRITestDataMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createMri();
    }

    private function createMri()
    {
        $testAgency = Agency::factory()->create();
        $testOffice = Office::factory()->make();
        $testOffice->agency_id = $testAgency->id;
        $testOffice->save();

        $testMriOffice = MriOffice::factory()->make();
        $testMriOffice->office_id = $testOffice->id;
        $testMriOffice->save();

        $testMriAgent = MriAgent::factory()->create();

        $testAgentProfile = AgentProfile::factory()->make();
        $testAgentProfile->office_id = $testOffice->id;
        $testAgentProfile->agency_id = $testOffice->agency_id;
        $testAgentProfile->save();

        $testUser = User::factory()->make();
        $testUser->email = $testMriAgent->email_address;
        $testUser->profile_type = AgentProfile::class;
        $testUser->profile_id = $testAgentProfile->id;
        $testUser->save();

        $testMriApplication = MriApplication::factory()->withAuthorizedPerson()->create();
        $testMriApplication->mriProperty()->save(MriProperty::factory()->make());
        $testMriProperty = $testMriApplication->mriProperty;
        $testMriProperty->agents = $testMriAgent->agent_id;
        $testMriProperty->mriAgents()->sync($testMriAgent);
    }
}
