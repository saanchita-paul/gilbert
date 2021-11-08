<?php

namespace Ignite\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IgniteResource extends JsonResource
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
            'applications' => empty($this->applications) ? 0 : $this->applications,
            'nonpayable' => empty($this->nonpayable) ? 0 : $this->nonpayable,
            'power' => empty($this->power) ? 0 : $this->power,
            'gas' => empty($this->gas) ? 0 : $this->gas,
            'internet' => empty($this->internet) ? 0 : $this->internet,
            'water' => empty($this->water) ? 0 : $this->water,
        ];
    }
}
