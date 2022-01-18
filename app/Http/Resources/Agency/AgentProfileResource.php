<?php

namespace App\Http\Resources\Agency;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Agency\SearchAgentProfileService;

class AgentProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['user']['roles'] = !empty($data['user']['roles']) ? $this->roles($data['user']['roles']) : [];
        $data['conversion_rate'] = $this->getConversionRate($request, $data['id']);
        $data['is_visa'] = 1;
        return  $data;
    }

    public function roles($roles): array
    {
        return collect($roles)->pluck('name')->toArray();
    }

    private function getConversionRate($request, $id)
    {
        $service = new SearchAgentProfileService($request->toArray());
        return $service->getConversionCount($id);
    }
}
