<?php

namespace MRI\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use MRI\Services\GetAgentService;
use MRI\Services\MapAgentService;

use Carbon\Carbon;

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

        try {
            $fetchAgentService = new GetAgentService();

            $message = '';

            if (empty($afterDate)) {
                $nowDate = Carbon::now()->format('Y-m-d');
                $fetchAgentService->setAfterDate($nowDate);
                $message = sprintf('Fetching data after date %s', $nowDate);
            }
            else if ($afterDate !== 'all') {
                $fetchAgentService->setAfterDate($afterDate);
                $message = sprintf('Fetching data after date %s', $afterDate);
            }
            else {
                $message = 'Fetching all data without after date';
            } 
            info($message);
            dump($message);

            $fetchAgentService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
        
        try {
            $mapAgentService = new MapAgentService();
            $mapAgentService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
        $this->line('MRI fetch agents command finished successfully!');
    }
}