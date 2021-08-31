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
    protected $signature = 'get:sales:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get every sales status between 2 dates';

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
        $service = new GetSalesRequestStaus();
        $service->getSalesStatusByDateRange();
        return 0;
    }
}
