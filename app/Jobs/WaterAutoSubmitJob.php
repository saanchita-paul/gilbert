<?php

namespace App\Jobs;

use App\Models\ConnectionApplication;
use Exception;
use Illuminate\Bus\Queueable;
use App\Mail\WaterSumissionFailed;
use Illuminate\Support\Facades\Mail;
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

    private function sendEmail($msg){
        $connectionApplication = ConnectionApplication::where('id' , $this->applicationId)->first();
        $dataToBeSent =  [
            'reason of failure' => $msg,
            'lead id'           =>  $connectionApplication->id,
        ];
        $emails =  explode( ',', config('water.support_emails'));
        foreach ($emails as $recipient) {
            Mail::to($recipient)->send(new WaterSumissionFailed($dataToBeSent));
        }
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
            // Saving Failed reason and set Water status as Failed 
            $service->saveRejectionReason($exception->getMessage(), $this->applicationId, 'water');
            $service->setStatusFailed($this->applicationId, 'water');

            WaterEmailService::sendEmailWhenSubmissionFails($exception->getMessage(), $this->applicationId);
            info('exception in handle method, WaterAutoSubmitJob', [$exception->getTraceAsString(), $exception->getMessage()]);
            throw new \Exception('Water submission failed, WaterAutoSubmitJob');
        }
    }
}

