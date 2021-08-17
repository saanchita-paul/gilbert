<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'tenancy_type' => $this->tenancy_type,
            'date_of_birth' => $this->dob,
            'moving_date' => $this->moving_date,
            'address_unit' => $this->address_unit,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'state' => $this->state,
            'country' => $this->country,
            'additional_instruction' => $this->additional_instruction,
            'address_text' => $this->address_text,
            'services' => $this->getConnectionServices($this->connectionServices),
            'identification' => $this->identification,
            'is_email_billing' => $this->is_email_billing,

        ];
    }

    private function getConnectionServices($services)
    {
        $service_array = [];
        $count = sizeof($services);
        for ($i = 0; $i < $count; $i++) {
            array_push($service_array,$services[$i]['service_type']);
        }
        return $service_array;
    }
}
