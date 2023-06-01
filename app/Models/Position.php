<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends BaseEntity
{
    use HasFactory, Sluggable;

    protected $table = 'positions';

    protected $guarded = ['deleted_at'];

    protected $dates = ['deleted_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function contacts() {
        return $this->belongsToMany(Contact::class, 'contact_positions', 'position_id', 'contact_id');
    }
}
