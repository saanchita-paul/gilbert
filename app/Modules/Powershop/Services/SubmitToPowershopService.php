<?php

namespace Powershop\Services;

use App\Models\ConnectionService;
use App\Models\ConnectionApplication;
use App\Models\RejectionReason;

use App\Services\PowerShop\SameDayConnectionService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use Carbon\Carbon;
use Exception;

class SubmitToPowershopService
{
    private ConnectionApplication $application;
    private string $submitType;
    private array $services;
    private array|Collection|ConnectionService|Model $conServices;

    public function __construct(int $application_id, string $submitType)
    {
        $this->application = ConnectionApplication::findOrFail($application_id);
        $this->submitType = $submitType;
        $this->services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };
        $this->conServices = ConnectionService::where('connection_application_id', $this->application->id)
        ->whereIn('service_type', $this->services)
        ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
        ->get();

        if (count($this->conServices) == 0) {
            throw new Exception('Unable to find connection services to submit');
        }
    }

    public function submit(){
        // TODO: validate connection date
        foreach ($this->conServices as $conService) {
            $availableDate = $this->application->moving_date;
            if ($conService->service_type == ConnectionService::TYPE_GAS){
                $newDateService = new SameDayConnectionService($this->application->id, ConnectionService::TYPE_GAS);
                $availableDate = $newDateService->getNextGasConnectionDate();
            } 
            $conService->connection_date = $availableDate;
            $conService->save();
        }

        $newSignUp = new SignUpService($this->application->id, $this->submitType);
        $results = $newSignUp->sendCustomerData();
        if (empty($results['reference'])){
            if (!empty($results['errors'])){
                HandleRejectionService::handleErrors($this->application->id, $this->services, $results['errors']);
                throw new Exception(sprintf('Sign Up submission rejected. Please refer to rejection reasons for app id %s', $this->application->id));
            }
            throw new Exception('Fail to fetch reference number after sign up');
        }

        foreach ($this->conServices as $conService) {
            $this->saveSubmittedStatus($conService->id, $results['reference']);
        }
    }

    public static function saveSubmittedStatus($serviceId, $reference)
    {
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_SUBMITTED;
        $service->quote_reference = $reference;
        $service->submitted_at = Carbon::now();
        $service->save();

        $application = $service->connectionApplication;
        $application->status = ConnectionApplication::STATUS_SUBMITTED;
        $application->save();
    }

}
