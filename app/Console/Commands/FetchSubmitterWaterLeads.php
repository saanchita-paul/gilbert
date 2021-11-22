<?php

namespace App\Console\Commands;

use App\Modules\FastConnect\Services\GetWaterService;
use Illuminate\Console\Command;

class FetchSubmitterWaterLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:submitted-water-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching submitted water list';

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
        $service = new GetWaterService();
        $service->getAllSubmittedWaterLead();

        return 0;
    }
}
