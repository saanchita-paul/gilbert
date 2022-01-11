<?php


namespace App\Http\Resources\Agency;


use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationNoteResourse extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_role' => $this->user_role,
            'leads' => json_decode($this->connection_details),
            'plans' => json_decode($this->plan_details)
        ];
    }
}
