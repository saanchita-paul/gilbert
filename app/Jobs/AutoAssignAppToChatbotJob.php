<?php

namespace App\Jobs;

use App\Services\Agency\AutoAssignApplicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoAssignAppToChatbotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $application;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $autoAssignService = new AutoAssignApplicationService();
            $autoAssignService->assignApplication($this->application);

            // Update Hubspot Contact auto assign
            UpdateHubspotContactJob::dispatch($this->application->id);
            Log::info('Auto assign application to chatbot successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
