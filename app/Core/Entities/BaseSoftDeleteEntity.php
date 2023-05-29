<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class BaseSoftDeleteEntity extends BaseEntity
{
    use SoftDeletes;

    protected $dates = [ 'deleted_at' ];
}
