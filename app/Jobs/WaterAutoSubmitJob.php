<?php

namespace App\Jobs;

use App\Models\ConnectionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Agency\WaterEmailService;
use App\Services\Agency\UpdatedWaterStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use FastConnect\Services\SubmitWaterLeadToFastConnect;

class WaterAutoSubmitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $applicationId;

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
        try
        {
        $service = new SubmitWaterLeadToFastConnect($this->applicationId);
        $result = $service->submitWaterLead();
        ConnectionApplication::saveFasConnectRef($this->applicationId, data_get($result, "info.customer_reference"));

        $statusAssoc = UpdatedWaterStatus::mapFromFCStatus(data_get($result, "products.0.status"));
        if ($statusAssoc) {
            UpdatedWaterStatus::updateStatus($this->applicationId, $statusAssoc['status'], $statusAssoc['reason']);
        }
        } catch(\Exception $exception)
        {
            WaterEmailService::sendEmailWhenSubmissionFails($exception->getMessage(), $this->applicationId);
            info('exception in handle method, WaterAutoSubmitJob', [$exception->getTraceAsString(), $exception->getMessage()]);
            throw new \Exception('Water submission failed, WaterAutoSubmitJob');
        }
    }
}

