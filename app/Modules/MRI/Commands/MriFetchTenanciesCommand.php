<?php

namespace MRI\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use MRI\Services\GetTenanciesService;
use MRI\Services\GetPropertyService;
use MRI\Services\MapApplicationService;

use Carbon\Carbon;

class MriFetchTenanciesCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mri:fetch_tenancies {afterDate?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Tenancies Data from MRI';

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
        $this->line('MRI fetch tenancies command started successfully!');

        try {
            $tenancyService = new GetTenanciesService();

            if (empty($afterDate)){
                $nowDate = Carbon::now()->format('Y-m-d');
                $tenancyService->setAfterDate($nowDate);
                dump(sprintf('Fetching data after date %s', $nowDate));
            }
            else if ($afterDate !== 'all') {
                $tenancyService->setAfterDate($afterDate);
                dump(sprintf('Fetching data after date %s', $afterDate));
            }
            else {
                dump('Fetching all data without after date');
            }
             
            $tenancyService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

        try {
            $propertyService = new GetPropertyService();
            $propertyService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

        try {
            $testService = new MapApplicationService();
            $testService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

        $this->line('MRI fetch tenancies command finished successfully!');
    }
}