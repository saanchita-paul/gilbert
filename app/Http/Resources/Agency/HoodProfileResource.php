<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HoodProfileResource extends JsonResource
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
        return  $data;
    }

    public function roles($roles): array
    {
        return collect($roles)->pluck('name')->toArray();
    }
}
