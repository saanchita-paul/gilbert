<?php

namespace MRI\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use MRI\Jobs\MriFetchAgentsJob;

class MriFetchAgentsCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mri:fetch_agents {afterDate?}';
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
        $afterDate = $this->argument('afterDate') ?? '';
        $this->line('MRI fetch agents command started successfully!');
        MriFetchAgentsJob::dispatch($afterDate);
        $this->line('MRI fetch agents command finished successfully!');
    }
}