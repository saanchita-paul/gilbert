<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'logo' => $this->logo,
            'app_source_id' => $this->app_source_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];
    }
}
