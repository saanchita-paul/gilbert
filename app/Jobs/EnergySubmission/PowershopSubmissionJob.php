<?php

namespace App\Jobs\EnergySubmission;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Powershop\Services\SubmitToPowershopService;


class PowershopSubmissionJob implements ShouldQueue
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

        $error = [];

        $allowedSubmitType = ['energy', 'power', 'gas'];
        if (in_array($submitType, $allowedSubmitType)) {
            try {
                $submitPowerShop = new SubmitToPowershopService($application->id, $submitType);
                $submitPowerShop->submit();
            }
            catch (\Exception $e) {
                $logError = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                $error = $e;
                \Log::error('PowershopSubmissionJob:handle - FAIL (Refer context for details)', $logError);
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
            info("Skipping Powershop Submit", [
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
            ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
            ->whereNull('lead_reference')
            ->first();

        if ($connectionService) {
            return true;
        }
        return  false;
    }
}
