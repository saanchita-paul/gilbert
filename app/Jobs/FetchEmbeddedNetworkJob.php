<?php

namespace App\Jobs;

use App\Events\FetchEmbeddedNetworkEvent;
use App\Models\ConnectionApplication;
use App\Services\Agency\MirnNmiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchEmbeddedNetworkJob implements ShouldQueue
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
        $this->onQueue('fc-address');
        $this->applicationId = $applicationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $application = ConnectionApplication::find($this->applicationId);
        MirnNmiService::fetchNmiIsEmbedded($application);

        $application->update(['loading_address_info' => false]);

        event(new FetchEmbeddedNetworkEvent($application->id));
    }
}
