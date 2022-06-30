<?php

namespace App\Jobs\EnergySubmission;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Sales\PostSalesService;
use App\Services\Utility\SumoService;
use App\thiss\Agency\SubmitApplicationthis;
use App\Models\ConnectionService;
use App\Services\Agency\HubspotContactService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Origin\Services\OriginService;

class SumoSubmissionJob implements ShouldQueue
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
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle()
    {
        $application = ConnectionApplication::whereId($this->applicationId)->with("connectionServices")->firstOrFail();
        $submitType = $this->submitType;
        $error = [];

        $allowedSubmitType = ['energy', 'power', 'gas'];
        if (in_array($submitType, $allowedSubmitType)) {
            try {
                $res = (new SumoService())->storeCustomerData($this->applicationId, $this->submitType);
                info("Sumo response body 1");
    //            \Log::info($res['status']);
                (new SumoService())->saveStatus($this->applicationId, $res['status'] , $res['creditCheck'], $submitType);
                ConnectionApplication::where('id' , $this->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
                info(json_encode($res));
                info("Sumo response body 2");
            } 
            catch (\Exception $e) {
                $error['message'] = $e->getMessage();
                $error['file'] = $e->getFile();
                $error['line'] = $e->getLine();
                \Log::error('SumoSubmissionJob:handle - FAIL (Refer context for details)', $error);
            }
            finally {
                $application->update([
                    'is_running_submission' => 0,
                ]);

                if (!empty($error)) {
                    throw new \Exception($error['message']);
                }
            }
        } else {
            info("Skipping Sumo Submit", [
                'submit_type' => $submitType,
                'is_services_valid' => $this->isProviderSumo($application, $submitType)
            ]);
        }

    }

    /**
     * @param $application
     * @return bool
     */
    private function isProviderSumo($application, $submitType): bool
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        foreach ($application->connectionServices as $service) {
            if ($service->provider_name === 'sumo' && in_array($service->service_type, $services)) {
                return true;
            }
        }
        return false;
    }
}
