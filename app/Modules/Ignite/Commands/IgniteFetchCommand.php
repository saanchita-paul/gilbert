<?php

namespace Ignite\Commands;

use Exception;
use Ignite\Jobs\IgniteFetchJob;
use Ignite\Services\IgniteLeadService;
use Illuminate\Console\Command;

class IgniteFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ignite:fetch {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it will fetch leads from ignite';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->option('test')) {
            $this->test();
        } else {
            $this->fetchLead();
        }
    }

    /**
     * @throws Exception
     */
    private function test()
    {
        if (app()->environment('production')) {
            throw new Exception("Testing is not possible in production environment");
        }


        $createLeadService = new IgniteLeadService();
        $createLeadService->dummyCreate();
    }

    /**
     * @throws Exception
     */
    public function fetchLead()
    {
        $createLeadService = new IgniteLeadService();
        $createLeadService->create();
    }

}
