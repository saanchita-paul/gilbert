<?php


namespace App\Services\Ea;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Utility\StateMapService;

class SetEaDistributorService
{

    public function __construct(public ConnectionApplication $connectionApplication, public array $servicesId)
    {

    }

    public function setDistributor()
    {
        $eaResponse = json_decode($this->getPlanDetails());
        $eleDistributor = data_get($eaResponse, 'distributor_name.electricity', null);
        $gasDistributor = data_get($eaResponse, 'distributor_name.gas', null);

        $this->updateService(ConnectionService::TYPE_ELECTRICITY, $eleDistributor);
        $this->updateService(ConnectionService::TYPE_GAS, $gasDistributor);
    }

    private function updateService($service, $distributor)
    {
        $selectedService = ConnectionService::query()
            ->where('connection_application_id', $this->connectionApplication->id)
            ->where('service_type', $service)->first();

        if(!empty($selectedService)) {
            $selectedService->distributor = $distributor;
            $selectedService->save();
        }
    }

    private function getPlanDetails()
    {
        $stateService = new StateMapService();
        $state = $stateService->getShortName($this->connectionApplication->state);
        $postCode = $this->connectionApplication->postcode;
        $eaPlanService = new EaPlanDetailsService($state, $postCode, $this->connectionApplication->id, $this->servicesId);
        return $eaPlanService->getPlanDetails();
    }


}
