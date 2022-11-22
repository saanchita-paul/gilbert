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
    protected $signature = 'mri:fetch_tenancies {--office=} {--afterDate=}';
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
        $officeId = $this->option('office') ?? '';
        $afterDate = $this->option('afterDate') ?? '';
        $this->line('MRI fetch tenancies command started successfully!');

        try {
            $tenancyService = new GetTenanciesService();

            $message = '';

            if (!empty($officeId)) {
                $message .= sprintf('(Office ID = %s) ', $officeId);
                $tenancyService->setOfficeId(intval($officeId));
            }

            if (empty($afterDate)){
                $nowDate = Carbon::now()->subDays(config('mri.sub_days'))->format('Y-m-d');
                $tenancyService->setAfterDate($nowDate);
                $message .= sprintf('Fetching data after date %s', $nowDate);
            }
            else if ($afterDate !== 'all') {
                $tenancyService->setAfterDate($afterDate);
                $message .= sprintf('Fetching data after date %s', $afterDate);
            }
            else {
                $message .= 'Fetching all data without after date';
            }

            info($message);
            dump($message);
             
            $tenancyService->run();
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

        try {
            $propertyService = new GetPropertyService();
            if (!empty($officeId)) {
                $propertyService->setOfficeId(intval($officeId));
            }
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