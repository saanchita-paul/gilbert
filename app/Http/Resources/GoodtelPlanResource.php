<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodtelPlanResource extends JsonResource
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
            'display_name' => $this->display_name,
            'name' => $this->name,
            'type' => $this->type,
            'mbps' => $this->mbps,
            'price' => $this->price,
            'details_url' => $this->details_url,
            'provider' => 'Goodtel',
            'payment_links' => GoodtelPlanPaymentLinkResource::collection($this->whenLoaded('paymentLinks'))
        ];
    }
}
