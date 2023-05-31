<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this['id'],
            'email' => $this['email'],
            'role' => new RoleResource($this['role']),
            'access_token' => $this['access_token'],
            'token_type' => $this['token_type']
        ];

        return $resource;
    }
}
