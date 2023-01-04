<?php

namespace App\Jobs;

use App\Services\Agency\MirnNmiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAdditionalInfoAddressJob implements ShouldQueue
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
        $this->onQueue('fast-connect');

        $this->applicationId = $applicationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        MirnNmiService::dispatchAllService($this->applicationId);
    }
}
