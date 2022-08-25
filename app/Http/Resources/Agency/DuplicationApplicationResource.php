<?php


namespace App\Http\Resources\Agency;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DuplicationApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'international_phone' => $this->international_phone,
            'homephone' => $this->homephone,
            'phone_type' => $this->phone_type,
            'moving_date' => $this->moving_date,
            'address_text' => $this->address_text,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'submitted_at' => $this->submittedAt() ?? '',
            'assigned_to' => $this->assigned_to,
            'agent_profile' => $this->assignedTo
        ];
    }

    private function submittedAt(){
        return $this->connectionServices?->pluck('submitted_at')?->sort()?->first();
    }

}


