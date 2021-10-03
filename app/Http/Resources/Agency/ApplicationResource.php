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
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'homephone' => $this->homephone,
            'phone_type' => $this->phone_type,
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
            'family_violance' => isset($this->family_violance) ? $this->family_violance : 3,
            'is_renovation_on' => isset($this->is_renovation_on) ? $this->is_renovation_on : 1,
            'has_electricity' => isset($this->has_electricity) ? $this->has_electricity : 1,
            'inspection_time' => $this->inspection_time,
            'is_email_billing' => $this->is_email_billing,
            'nmi' => $this->nmi,
            'mirn' => $this->mirn,
            'property_type' => $this->property_type,
            'has_life_support' => $this->has_life_support,
            'has_solar' => $this->has_solar,
            'office_id' => $this->office_id,
            'agency_id' => $this->agency_id,
            'created_by' => $this->created_by,
            'assigned_to' => $this->assigned_to,
            'agent_profile' => $this->assignedTo,
            'status' => $this->status,
            'street_number' => $this->street_number,
            'unit_number' => $this->unit_number,
            'billing_unit_number' => $this->billing_unit_number,
            'billing_street_number' => $this->billing_street_number,
            'billing_street_name' => $this->billing_street_name,
            'billing_address_text' => $this->billing_address_text,
            'billing_address_unit' => $this->billing_address_unit,
            'billing_street_address' => $this->billing_street_address,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_postcode' => $this->billing_postcode,
            'is_billing_same' => $this->is_billing_same,
            'authorizedPersonName' => isset($this->authorizedPerson) ? "{$this->authorizedPerson->first_name} {$this->authorizedPerson->middle_name} {$this->authorizedPerson->last_name}" : null ,
        ];
    }

    private function getConnectionServices($services)
    {
        $service_array = [];
        $count = sizeof($services);
        for ($i = 0; $i < $count; $i++) {
            array_push($service_array, $services[$i]['service_type']);
        }
        return $service_array;
    }
}
