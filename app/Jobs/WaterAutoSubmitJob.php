<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use FastConnect\Services\SubmitWaterLeadToFastConnect;

class WaterAutoSubmitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applicationId)
    {
        //
            try {
                $service = new SubmitWaterLeadToFastConnect($applicationId);
                $result = $service->submitWaterLead();

                info( json_encode( $result ));

            } catch (\Exception $exception) {
                \Log::error('Problem in Water auto submit Job ');
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
            }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
