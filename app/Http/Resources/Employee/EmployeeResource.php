<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Position\PositionResourceCollection;
use App\Http\Resources\Unit\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'type' => $this->type,
            'unit' => new UnitResource($this->unit),
            'positions' => new PositionResourceCollection($this->positions),
            'nick_name' =>$this->nick_name,
            'full_name' =>$this->full_name,
            'join_date' =>$this->join_date
        ];

        return $resource;
    }
}
