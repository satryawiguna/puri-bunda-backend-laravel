<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Position\PositionResourceCollection;
use App\Http\Resources\Unit\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TopTenUserByLoginResourceCollection extends ResourceCollection
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
                'id' => $value->contact->id,
                'type' => $value->contact->type,
                'unit' => new UnitResource($value->contact->unit),
                'positions' => new PositionResourceCollection($value->contact->positions),
                'nick_name' =>$value->contact->nick_name,
                'full_name' =>$value->contact->full_name,
                'join_date' =>$value->contact->join_date
            ];
        });

        return $resources->toArray();
    }
}
