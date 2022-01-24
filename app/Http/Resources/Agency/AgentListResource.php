<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentListResource extends JsonResource
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
        return  $data;
    }
}
