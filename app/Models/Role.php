<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $guarded = ['deleted_at', 'request_by'];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
