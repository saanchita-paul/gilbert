<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManualStatusChangeLogResource extends JsonResource
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
            'changed_by' => $this->changed_by,
            'title' => $this->title,
            'status_change_reason' => $this->status_change_reason,
            'data' => $this->data,
            'user_role' => $this->user_role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
