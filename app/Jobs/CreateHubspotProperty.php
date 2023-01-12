<?php

namespace App\Jobs;

use App\Services\hubspot\HubspotHandlerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateHubspotProperty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $applicationId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $applicationId)
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
        $handler = new HubspotHandlerService($this->applicationId);
        $handler->handle();
    }
}
