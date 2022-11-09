<?php

namespace MRI\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use MRI\Services\GetTenanciesService;
use MRI\Services\GetPropertyService;

class MriFetchTenanciesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $afterDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($afterDate = '')
    {
        $this->afterDate = $afterDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exceptionsArray = [];
        try {
            $tenancyService = new GetTenanciesService();
            if (!empty($this->afterDate)){
                $tenancyService->setAfterDate($this->afterDate);
            }
            $tenancyService->run();
        } catch (\Exception $exception) {
            $exceptionsArray['tenancy'] = $exception;
        }

        try {
            $propertyService = new GetPropertyService();
            $propertyService->run();
        } catch (\Exception $exception) {
            $exceptionsArray['property'] = $exception;
        }

        if (!empty($exceptionsArray)){
            $e = $exceptionsArray['tenancy'] ?? $exceptionsArray['property'];
            throw $e;
        }
    }

}