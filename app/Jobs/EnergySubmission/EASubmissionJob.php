<?php

namespace App\Jobs\EnergySubmission;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Sales\PostSalesService;
use App\thiss\Agency\SubmitApplicationthis;
use App\Models\ConnectionService;
use App\Services\Agency\HubspotContactService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Origin\Services\OriginService;

class EASubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $applicationId;
    private string $submitType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $applicationId, string $submitType)
    {
        $this->applicationId = $applicationId;
        $this->submitType = $submitType;
    }

    /**
     * Handle the this.
     *
     * @return void
     * @throws \Exception
     */
    /**
     * Handle the event.
     *
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle()
    {
        $submitType = $this->submitType;
        $application = ConnectionApplication::with('connectionServices')->where('id', $this->applicationId)->firstOrFail();
        $saleApiOn = config('ea.is_sales_api_on');
        $error = [];

        $allowedSubmitType = ['energy', 'power', 'gas'];
        if ($saleApiOn === "1" &&
            in_array($submitType, $allowedSubmitType)) {
            try {
                $postEaService = new PostSalesService($this->applicationId);
                $postEaService->postToEa($submitType, $this->applicationId);
                ConnectionApplication::where('id' , $this->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
                $hubspotService = new HubspotContactService($this->applicationId);
                $hubspotService->update();
            }
            catch (\Exception $e) {
                $logError = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                $error = $e;
                \Log::error('EASubmissionJob:handle - FAIL (Refer context for details)', $logError);
            }
            finally {
                $application->update([
                    'is_running_submission' => 0,
                ]);

                if (!empty($error)) {
                    throw $error;
                }
            }
        } else {
            info("Skipping EA Submit", [
                'EA_SALES_API_ON' => $saleApiOn,
                'submit_type' => $submitType,
                'is_services_valid' => $this->isValidForSalesApi($application, $submitType)
            ]);
        }
    }

    /**
     * @param $application
     * @return bool
     */
    private function isValidForSalesApi($application, $submitType): bool
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        $connectionService = ConnectionService::where('connection_application_id', $application->id)
            ->whereIn('service_type', $services)
            ->where('provider_name' , '=', 'ea')
            ->whereNull('lead_reference')
            ->first();

        if ($connectionService) {
            return true;
        }
        return  false;
    }
}
