<?php


namespace App\Services\FirstEnergy;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class FirstEnergyService
{

    public function __construct(public ConnectionApplication $connectionApplication, public array $servicesId, public $submitType)
    {

    }

    public function setServiceStatus()
    {
        if (in_array($this->submitType, ['energy', 'power'])) {
            $this->updateServiceStatus(ConnectionService::TYPE_ELECTRICITY);
        }
        if (in_array($this->submitType, ['energy', 'gas'])) {
            $this->updateServiceStatus(ConnectionService::TYPE_GAS);
        }
    }

    private function updateServiceStatus($service)
    {
        $selectedService = ConnectionService::query()
            ->where('connection_application_id', $this->connectionApplication->id)
            ->where('service_type', $service)->first();

        if(!empty($selectedService)) {
            $selectedService->status = ConnectionService::STATUS_SUBMITTED;
            $selectedService->save();
        }
    }
}
