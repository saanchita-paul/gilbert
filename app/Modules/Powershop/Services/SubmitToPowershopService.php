<?php

namespace Powershop\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Powershop\Services\SameDayConnectionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
            throw new Exception('Unable to find connection Services to submit');
        }
    }

    public function submit(){
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
                $rejections = HandleRejectionService::handleErrors($this->application->id, $this->services, $results['errors']);
                \Log::error('Powershop rejected. Refer to context for list of rejection reasons', $rejections);
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
        $service->status = ConnectionService::STATUS_ACCEPTED;
        $service->quote_reference = $reference;
        $service->submitted_at = Carbon::now();
        $service->save();

        $application = $service->connectionApplication;
        $application->status = ConnectionApplication::STATUS_SUBMITTED;
        $application->save();
    }

}
