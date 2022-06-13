<?php

namespace App\Jobs\EnergySubmission;

use App\thiss\Agency\SubmitApplicationthis;
use App\Models\ConnectionService;
use App\Services\Agency\HubspotContactService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Origin\Services\OriginService;

class OriginSubmissionJob implements ShouldQueue
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
    public function handle()
    {
        $allowedSubmitType = ['energy', 'power', 'gas'];

        if (in_array($this->submitType, $allowedSubmitType))
        {
            $originService = new OriginService($this->applicationId);
            match ($this->submitType) {
                'energy' => $this->storeBothElectricityAndGas($originService),
                'power' => $originService->storeElectricity(),
                'gas' => $originService->storeGas(),
            };
        } else {
            info("Skipping Origin Submit", [
                'submit_type' => $this->submitType,
                'is_services_valid' => $this->isValidForOrigin($this->applicationId, $this->submitType)
            ]);
        }
    }

    private function storeBothElectricityAndGas($originService)
    {
        info("Submitting both Power and Gas to Origin");

        try {
            $originService->storeElectricity();
        }
        catch (\Exception $e){
            info("Skipping To Gas Submission");
        }

        $originService->storeGas();
    }

    /**
     * @param $applicationId and $submitType
     * @return bool
     */
    private function isValidForOrigin($applicationId, $submitType): bool
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        $connectionService = ConnectionService::where('connection_application_id', $applicationId)
            ->whereIn('service_type', $services)
            ->where('provider_name' , '=', 'origin')
            ->whereNotNull('plan_type')
            ->first();

        if ($connectionService) {
            return true;
        }
        return  false;
    }
}
