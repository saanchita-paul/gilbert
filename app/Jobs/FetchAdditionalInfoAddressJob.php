<?php

namespace App\Jobs;

use App\Models\ConnectionApplication;
use App\Notifications\FetchEmbeddedNetworkNotification;
use App\Notifications\FetchMirnNmiNotification;
use App\Services\Agency\MirnNmiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
        MirnNmiService::fetchMirnNmi($application->id);
        $application->notify(new FetchMirnNmiNotification($application->id));
        MirnNmiService::fetchIsEmbedded(null, true, $application->id);
        $application->notify(new FetchEmbeddedNetworkNotification($application->id));
        $application->update(['loading_address_info' => false]);
    }
}
