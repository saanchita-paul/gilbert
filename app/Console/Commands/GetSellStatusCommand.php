<?php

namespace App\Console\Commands;

use App\Services\Sales\GetSalesRequestStaus;
use Illuminate\Console\Command;

class GetSellStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:submitted-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch submitted lead from ea';

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
     * @return int
     */
    public function handle()
    {
        $this->checkStatus();
        return 0;
    }

    private function checkStatus()
    {
        $service = new GetSalesRequestStaus();
        $service->fetchAllSubmittedLead();
    }
}
