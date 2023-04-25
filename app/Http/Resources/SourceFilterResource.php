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
            'email' => $this->email,
            'source_type' => $this->source_type,
            'name' => $this->name,
            'logo' => $this->logo,
            'source_id' => $this->source_id,
            'table_name' => $this->table_name,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'default_office_id' => $this->default_office_id,
        ];
    }
}
