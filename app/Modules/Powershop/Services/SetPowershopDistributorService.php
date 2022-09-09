<?php


namespace Powershop\Services;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Utility\StateMapService;
use Illuminate\Support\Facades\Log;

class SetPowershopDistributorService
{

    public function __construct(public ConnectionApplication $connectionApplication, public array $servicesId, public $submitType)
    {

    }

    public function setDistributor()
    {
        $response = json_decode($this->getPlanDetails());
        $eleDistributor = data_get($response, 'plans.electricity.distributor_name', null);
        $gasDistributor = data_get($response, 'plans.gas.distributor_name', null);

        if (in_array($this->submitType, ['energy', 'power'])) {
            $this->updateService(ConnectionService::TYPE_ELECTRICITY, $eleDistributor);
        }
        if (in_array($this->submitType, ['energy', 'gas'])) {
            $this->updateService(ConnectionService::TYPE_GAS, $gasDistributor);
        }
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
        
        $powershopPlanService = new PowershopPlanDetailsService($state, $postCode, $this->connectionApplication->id, $this->servicesId, $this->connectionApplication->nmi ?? '');
        return $powershopPlanService->getPlanDetails();
    }

}