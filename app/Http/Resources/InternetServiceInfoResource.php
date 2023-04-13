<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternetServiceInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'connection_application_id' => $this->connection_application_id,
            'connection_service_id' => $this->connection_service_id,
            'is_shipping_same' => $this->is_shipping_same,
            'unit_number' => $this->unit_number,
            'street_number' => $this->street_number,
            'street_name_only' => $this->street_name_only,
            'address_text' => $this->address_text,
            'street_address' => $this->street_address,
            'street_type' => $this->street_type,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'state' => $this->state,
            'is_need_home_phone' => $this->is_need_home_phone,
            'is_back_to_base' => $this->is_back_to_base,
            'is_security_alarm' => $this->is_security_alarm,
            'is_existing_landline' => $this->is_existing_landline,
            'home_phone_number' => $this->home_phone_number,
            'current_provider' => $this->current_provider,
            'account_number' => $this->account_number,
            'home_phone_provider' => $this->home_phone_provider,
            'home_phone_plan' => $this->home_phone_plan,
            'otp' => $this->otp,
            'modem_type' => $this->modem_type,
            'charity' => $this->charity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'connection_service' => $this->connectionService,
            'is_caf_generated' => $this->is_caf_generated
        ];
    }
}
