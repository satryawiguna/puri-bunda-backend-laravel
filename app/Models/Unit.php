<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends BaseEntity
{
    use HasFactory, Sluggable;

    protected $table = 'units';

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

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'unit_id');
    }
}
