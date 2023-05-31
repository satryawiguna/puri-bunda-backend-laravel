<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Position\PositionResourceCollection;
use App\Http\Resources\Unit\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resources = $this->collection->map(function ($value, $key) {
            return [
                'id' => $value->id,
                'type' => $value->type,
                'unit' => new UnitResource($value->unit),
                'positions' => new PositionResourceCollection($value->positions),
                'nick_name' =>$value->nick_name,
                'full_name' =>$value->full_name,
                'join_date' =>$value->join_date
            ];
        });

        return $resources->toArray();
    }
}
