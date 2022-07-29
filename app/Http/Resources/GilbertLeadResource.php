<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GilbertLeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return[
            'personal_details' => [
                'id' => $this->id,
                'title'=> $this->title,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'dob'=> $this->dob,
            ],
            'contact_details' => [
                'phone' => $this->phone,
                'email' => $this->email,
            ],
            'connection_details' => [
                'moving_date' => $this->moving_date,
                'address_unit' => $this->address_unit,
                'street_address' => $this->street_address,
                'street_number'=> $this->street_number,
                'postcode' => $this->postcode,
                'state' => $this->state,
                'city' => $this->city,
                'street_type' => $this->street_type,
                'street_name_only' => $this->street_name_only,
                'country' => $this->country,
                'address_text' => $this->address_text,
                ],
            'billing_details' => [
                'billing_unit_number' => $this->billing_unit_number,
                'billing_street_number' => $this->billing_street_number,
                'billing_street_name' => $this->billing_street_name,
                'billing_address_unit' => $this->billing_address_unit,
                'billing_city' => $this->billing_city,
                'billing_state' => $this->billing_state,
                'billing_postcode' => $this->billing_postcode,
                'billing_street_type' => $this->billing_street_type,
                'billing_mannual_address' => $this->billing_mannual_address,
                'billing_state_short' => $this->billing_state_short,
                'billing_street_name_only' => $this->billing_street_name_only,
                'billing_address_text' => $this->billing_address_text,
                'billing_street_address' => $this->billing_street_address,
                ],
            'property_details' => [
                'property_type' => $this->property_type,
                'tenancy_type' => $this->tenancy_type,
                'has_solar' => $this->has_solar,
            ],
            'id_details' => [
                'concession_card_type' => $this->concession_card_type,
                'concession_card_number' => $this->concession_card_number,
                'concession_start_date' => $this->concession_start_date,
                'concession_end_date' => $this->concession_end_date
            ]
        ];
    }

}
