<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationMetricsResource extends JsonResource
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
            'applications' => $this->applications,
            'nonpayable' => $this->nonpayable,
            'power' => $this->power,
            'gas' => $this->gas,
            'internet' => $this->internet,
            'water' => $this->water,
        ];
    }
}
