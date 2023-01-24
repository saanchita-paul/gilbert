<?php


namespace Origin\Services;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Utility\StateMapService;
use Illuminate\Support\Facades\Log;

class SetOriginDistributorService
{

    public function __construct(public ConnectionApplication $connectionApplication, public array $servicesId, public $submitType)
    {

    }

    public function setDistributor()
    {
        $response = json_decode($this->getPlanDetails());
        $eleDistributor = data_get($response, 'plans.electricity.distributor_name', null);
        $gasDistributor = data_get($response, 'plans.gas.distributor_name', null);

        match ($this->submitType) {
            'energy' => $this->updateService(ConnectionService::TYPE_ELECTRICITY, $eleDistributor) && $this->updateService(ConnectionService::TYPE_GAS, $gasDistributor),
            'power' =>  $this->updateService(ConnectionService::TYPE_ELECTRICITY, $eleDistributor),
            'gas' => $this->updateService(ConnectionService::TYPE_GAS, $gasDistributor),
            default => null,
        };
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

        $originPlanService = new OriginPlanDetailsService($state, $postCode, $this->connectionApplication->id, $this->servicesId, $this->connectionApplication->nmi ?? '');
        return $originPlanService->getPlanDetails();
    }


}
