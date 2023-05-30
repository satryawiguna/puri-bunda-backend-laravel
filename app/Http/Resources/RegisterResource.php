<?php

namespace App\Http\Resources;

use App\Http\Resources\Common\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $resource = [
            'id' => $this->id,
            'role' => new RoleResource($this->role),
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at->timestamp,
            'updated_at' => $this->updated_at->timestamp,
        ];

        return $resource;
    }
}
