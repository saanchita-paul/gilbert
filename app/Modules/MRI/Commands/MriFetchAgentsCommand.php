<?php

namespace MRI\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use MRI\Services\GetAgentService;
use MRI\Services\MapAgentService;
use Carbon\Carbon;
use MRI\Services\MriServices;

class MriFetchAgentsCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mri:fetch_agents {--office=} {--afterDate=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Agents Data from MRI';

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
    public function handle()
    {
        $officeId = $this->option('office') ?? '';
        $afterDate = $this->option('afterDate') ?? '';
        $this->line('MRI fetch agents command started successfully!');
        try {
            MriServices::handleFetchAgents($officeId, $afterDate);
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
        try {
            MriServices::handleMapAgents();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
        $this->line('MRI fetch agents command finished successfully!');
    }
}
