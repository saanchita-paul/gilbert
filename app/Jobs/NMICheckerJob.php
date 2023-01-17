<?php

namespace App\Jobs;

use App\Models\ConnectionApplication;
use App\Services\Agency\MirnNmiService;
use App\Services\FastConnectService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NMICheckerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private int $applicationId)
    {
        $this->onQueue('speed');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        MirnNmiService::saveApplicationMirnNmi($this->applicationId);
    }
}
