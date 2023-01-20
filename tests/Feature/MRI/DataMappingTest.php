<?php

namespace Tests\Feature\MRI;

use App\Models\MriAgent;
use App\Models\AgentProfile;
use App\Models\MriApplication;
use MRI\Services\MapApplicationService;
use App\Models\User;
use App\Models\Office;
use Database\Seeders\MRITestDataMappingSeeder;
use Database\Seeders\MRITestDataMappingUnregisteredSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MRI\Services\MapAgentService;
use Tests\TestCase;

class DataMappingTest extends TestCase
{
    use DatabaseTransactions;

    public function testDataMappingAgentSuccess()
    {
        $this->seed(MRITestDataMappingSeeder::class);

        $testService = new MapAgentService();
        $testService->run();

        $testAgentProfile = AgentProfile::orderBy('id', 'desc')->first();
        $testMriAgent = MriAgent::orderBy('id', 'desc')->first();

        $this->assertTrue($testMriAgent->agent_profile_id == $testAgentProfile->id, sprintf('Expected profile id save is %s, result is %s', $testAgentProfile->id, $testMriAgent->agent_profile_id ?? 'null'));
    }

    public function testDataMappingAgentFail()
    {
        $this->seed(MRITestDataMappingUnregisteredSeeder::class);

        $testService = new MapAgentService();
        $testService->run();

        $testMriAgent = MriAgent::orderBy('id', 'desc')->first();

        $this->assertNull($testMriAgent->agent_profile_id);
    }

    public function testDataMappingApplication()
    {
        $this->seed(MRITestDataMappingSeeder::class);
        $this->seed(MRITestDataMappingUnregisteredSeeder::class);
        $testMriApplication = MriApplication::orderBy('id', 'desc')->first();

        $testService = new MapAgentService();
        $testService->run();
        $testService = new MapApplicationService();
        $testService->run();

        $this->assertTrue(!$testService->exceptionHandler->hasExceptions());
        $this->assertDatabaseHas('connection_applications', [
            'mri_application_id' => $testMriApplication->id,
        ]);
    }
}
