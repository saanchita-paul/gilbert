<?php

namespace App\Jobs;

use App\Services\GilbertToCB\GilbertToChatbotSyncService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GilbertToChatbotSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int $applicationId
     */
    private int $applicationId;

    /**
     * Create a new job instance.
     *
     * @param $applicationId
     */
    public function __construct($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $service = new GilbertToChatbotSyncService($this->applicationId);
        $service->sync();
    }
}
