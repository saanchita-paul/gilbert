<?php


namespace Powershop\Services;

use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PowershopPlanDetailsService
{
    public string $service_type;
    public string $plan_type = '';


    public function __construct(
        public string $state,
        public string $postcode,
        public int $leadId,
        public array $servicesId,
    )
    {
        $this->chatbotUri = config('bot.root_url');
        $this->service_type = $this->mapServiceForPlan();
    }

    public function getPlanDetails()
    {
        // TODO : get plan details from chatbot api
        $data = [];

        return json_encode($data);
    }

    private function mapServiceForPlan()
    {
        $gasService = ConnectionService::query()
            ->where('connection_application_id',  $this->leadId )
            ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP )
            ->where('service_type', ConnectionService::TYPE_GAS)
            ->whereIn('id', $this->servicesId)
            ->first();

        $eleService = ConnectionService::query()
            ->where('connection_application_id',  $this->leadId )
            ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
            ->whereIn('id', $this->servicesId)
            ->first();

        $powerFlag = false;
        $gasFlag = false;

        if($gasService) {
            $this->plan_type = $gasService->plan_type;
            $gasFlag = true;
        }

        if($eleService) {
            $this->plan_type = $eleService->plan_type;
            $powerFlag = true;
        }

        if($powerFlag && $gasFlag) {
            return 'electricity_and_gas';
        }
        else if($powerFlag) {
            return 'electricity';
        }
        else if($gasFlag) {
            return 'gas';
        }
        return '';

    }
}
