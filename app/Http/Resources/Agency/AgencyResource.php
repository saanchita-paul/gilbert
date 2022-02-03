<?php

namespace App\Http\Resources\Agency;

use App\Services\Agency\SearchAgencyService;
use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'offices_count' => $this->offices_count,
            'applications_count' => $this->applications_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_application' => $this->last_application,
            'conversion_rate' => $this->getConversionRate($request, $this->id),
            'active_user_count' => $this->getActiveUserCount($request, $this->id),
            'rent_roll_count' => $this->getRentRollCount($request, $this->id),
        ];
    }

    private function getConversionRate($request, $id)
    {
        $service = new SearchAgencyService($request->toArray());
        return $service->getConversionCount($id);
    }

    private function getActiveUserCount($request, $id)
    {
        $service = new SearchAgencyService($request->toArray());
        return $service->getActiveUserCount($id);
    }

    private function getRentRollCount($request, $id)
    {
        $service = new SearchAgencyService($request->toArray());
        return $service->getRentRollCount($id);
    }
}
