<?php

namespace App\Jobs;

use App\Services\Agency\AutomaticAssignToTSAService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoAssignToTSAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $applicationId;

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
            AutomaticAssignToTSAService::setAutomaticAssignToTSA($this->applicationId);
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
        }
    }
}
