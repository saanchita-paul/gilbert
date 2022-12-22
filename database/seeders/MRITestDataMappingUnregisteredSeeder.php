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

class MRITestDataMappingUnregisteredSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createMriUnregistered();
    }

    private function createMriUnregistered()
    {
        MriOffice::factory()->create();
        $testMriAgent = MriAgent::factory()->create();
        $testMriApplication = MriApplication::factory()->withAuthorizedPerson()->create();

        $testMriApplication->mriProperty()->save(MriProperty::factory()->make());
        $testMriProperty = $testMriApplication->mriProperty;
        $testMriProperty->agents = $testMriAgent->agent_id;
        $testMriProperty->mriAgents()->sync($testMriAgent);
    }
}
