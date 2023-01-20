<?php

namespace Tests\Feature\MRI;

use App\Models\MriOffice;
use App\Models\Office;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use MRI\Services\GetAgentService;
use App\Services\MRI\MriApplicationKeyService;
use Database\Seeders\MRISeeder;
use MRI\Services\GetPropertyService;
use MRI\Services\GetTenanciesService;
use MRI\Services\GetNotesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FetchTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test fetching application key via API
     *
     * 1. service returns array
     * 2. check expected keys in array objects
     * 3. check hood office exists
     */
    public function testFetchApplicationKey()
    {
        $testService = new MriApplicationKeyService();
        $response = $testService->getData();
        $this->assertIsArray($response);
        $firstData = $response[0];
        $this->assertArrayHasKey('key', $firstData);
        $this->assertArrayHasKey('company_name', $firstData);
        $this->assertArrayHasKey('activation_date', $firstData);
        $response = array_filter($response, function ($v) {
            return str_contains(strtolower($v['company_name']), 'hood');
        });
        $this->assertTrue(count($response) > 0);
    }

    /**
     * Test MRI Seeder already run
     */
    public function testMriSeeder()
    {
        $office = Office::where('name', 'MRI Hood Office')->first();
        $this->assertInstanceOf(Office::class, $office);
    }

    /**
     * Test fetching agent details via API
     */
    public function testFetchAgent()
    {
        $testService = new GetAgentService();
        $testService->setAfterDate('2022-05-01');
        $testService->run();
        $this->assertTrue(!$testService->exceptionHandler->hasExceptions());
    }

    /**
     * Test fetching tenancy details via API
     */
    public function testFetchTenancies()
    {
        $testService = new GetTenanciesService();
        $testService->setAfterDate('2022-05-01');
        $testService->run();
        $this->assertTrue(!$testService->exceptionHandler->hasExceptions());

        $testService = new GetPropertyService();
        $testService->run();
        $this->assertTrue(!$testService->exceptionHandler->hasExceptions());

        $testService = new GetNotesService();
        $testService->setAfterDate('2022-05-01');
        $testService->run();
        $this->assertTrue(!$testService->exceptionHandler->hasExceptions());
    }
}
