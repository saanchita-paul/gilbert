<?php

namespace App\Jobs;

use Origin\Services\CheckOrderAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Agency\HubspotContactService;

class OriginStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $lead_reference, private string $connection_application_id)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkOrder = new CheckOrderAPI($this->lead_reference);
        $checkOrder->fetch();
        $hubspotService = new HubspotContactService($this->connection_application_id);
        $hubspotService->update();
    }
}
