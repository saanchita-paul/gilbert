<?php

namespace App\Console\Commands;

use App\Modules\FastConnect\Services\UpdateWaterLeadsStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;

class UpdateWaterStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'water:update-status {--all}';

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
        UpdateWaterLeadsStatus::run(['all' => $this->option('all')]);

        return 0;
    }
}
