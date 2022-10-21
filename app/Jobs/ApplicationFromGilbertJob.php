<?php

namespace App\Jobs;

use App\Services\GilbertToCB\UpdateApplicationFromGilbertService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplicationFromGilbertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $applicationId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $application = new UpdateApplicationFromGilbertService($this->applicationId);
            $application->call();
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
        }
    }
}
