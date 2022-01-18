<?php


namespace App\Services\Ea;

use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EaPlanDetailsService
{
    public string $service_type;
    public string $plan_type = '';


    public function __construct(
        public string $state,
        public string $postcode,
        public int $leadId,
        public array $servicesId

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
            'plan_type' => $this->plan_type,
            'postcode' => $this->postcode,
        ];

        try{
            $response = Http::get($this->chatbotUri.'/hood-dashboard/api/ea-plans/'.$this->plan_type, $query);
            return json_encode(json_decode($response->body())->data);
        } catch (\Exception $e)
        {
            Log::error("[EA Plan Error] ->  " .$e->getMessage());
            Log::error($e->getTraceAsString());
            return  '';
        }
    }

    private function mapServiceForPlan()
    {
        $gasService = ConnectionService::query()
            ->where('connection_application_id',  $this->leadId )
            ->where('provider_name', ConnectionService::PROVIDER_EA )
            ->where('service_type', ConnectionService::TYPE_GAS)
            ->whereIn('id', $this->servicesId)
            ->first();

        $eleService = ConnectionService::query()
            ->where('connection_application_id',  $this->leadId )
            ->where('service_type', ConnectionService::TYPE_ELECTRICITY)
            ->where('provider_name', ConnectionService::PROVIDER_EA )
            ->whereIn('id', $this->servicesId)
            ->first();

        $powerFlag = false;
        $gasFlag = false;
        if(!is_null($gasService)) {
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
