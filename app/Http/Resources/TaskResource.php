<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 return [
        'id' => $this->id,
        'title' => $this->title,

        'completed' => (bool) $this->is_completed, 
        'user' => [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ],
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
       }
}
