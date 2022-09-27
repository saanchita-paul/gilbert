<?php


namespace Powershop\Services;

use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PowershopPlanDetailsService
{
    public string $service_type;
    public string $elec_plan_type = '';
    public string $gas_plan_type = '';


    public function __construct(
        public string $state,
        public string $postcode,
        public int $leadId,
        public array $servicesId,
        public string $nmi = '',
    )
    {
        $this->chatbotUri = config('bot.root_url');
        $this->service_type = $this->mapServiceForPlan();
    }

    public function getPlanDetails()
    {
        $query = [
            'state' => $this->state,
            'service_type' => $this->service_type,
            'postcode' => $this->postcode,
        ];

        if (!empty($this->nmi)) {
            $query['nmi_prefix'] = substr($this->nmi, 0, 2);
        }

        try{
            $response = Http::withOptions([
                "verify" => false,
            ])->get($this->chatbotUri.'/hood-dashboard/api/power-shop-plan-details', $query);
            Log::info("Powershop Plan Response: ", [json_encode(json_decode($response->body())->data)]);
            return json_encode(json_decode($response->body())->data);
        } catch (\Exception $e)
        {
            Log::error("[Powershop Plan Error] ->  " .$e->getMessage());
            Log::error($e->getTraceAsString());
            return  '';
        }
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
            $this->gas_plan_type = $gasService->plan_type;
            $gasFlag = true;
        }

        if($eleService) {
            $this->elec_plan_type = $eleService->plan_type;
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
