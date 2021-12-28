<?php

namespace App\Http\Resources\Agency;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ConnectionService;

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
        $data['application_details'] = $this->getApplicationDetails($data['id'], $data['created_applications']);
        unset($data['created_applications']);
        return  $data;
    }

    public function roles($roles): array
    {
        return collect($roles)->pluck('name')->toArray();
    }

    private function getApplicationDetails($id, $applications)
    {
        return [
            'application_count' => count($applications),
            'last_submitted_application' => $this->getLastCreatedApplication($applications),
            'conversion_rate' => $this->getConversionRate($id),
            'visa' => 1
        ];
    }

    private function getLastCreatedApplication($applications)
    {
        $max = null;
        foreach($applications as $application) {
            if( new DateTime($application['created_at']) > $max) {
                $max = new DateTime($application['created_at']);
            }
        }
        return $max;
    }

    private function getConversionRate($id)
    {
        $this->officeId = $id;
        $totalConnected = ConnectionService::query()
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_by', '=', $this->officeId);
            })
            ->whereIn('status', [ConnectionService::STATUS_ACCEPTED])
            ->count();

        $totalSubmitted = ConnectionService::query()
            ->whereHas('connectionApplication', function ($query) {
                $query->where('created_by', '=', $this->officeId);
            })
            ->whereIn('status', [
                ConnectionService::STATUS_ACCEPTED,
                ConnectionService::STATUS_REJECTED,
                ConnectionService::STATUS_ENERGY_SUBMIT,
                ConnectionService::STATUS_CLOSED,
                ConnectionService::STATUS_CANT_CONNECT,
                ConnectionService::AC_MANUAL_PROCESSING
            ])
            ->count();
        if($totalConnected === 0 && $totalSubmitted === 0) {
            return 0;
        } else {
            return number_format((($totalConnected / $totalSubmitted) * 100), 1);
        }
    }
}
