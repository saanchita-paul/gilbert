<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\GilbertToCB\GilbertToChatbotService;
use App\Services\RolePermission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GilbertToChatbotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $applicationId;

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
     */
    public function handle()
    {
        $application = new GilbertToChatbotService($this->applicationId);
        $application->create();
    }
}
