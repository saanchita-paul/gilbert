<?php

namespace App\Jobs;

use App\Modules\FastConnect\Services\UpdateWaterLeadsStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetWaterProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $id;
    private string $fast_connect_customer_reference;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $id,  $fast_connect_customer_reference)
    {
        $this->id = $id;
        $this->fast_connect_customer_reference = $fast_connect_customer_reference;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new UpdateWaterLeadsStatus();
        $service->getSubmittedDetails($this->id, $this->fast_connect_customer_reference);
    }
}
