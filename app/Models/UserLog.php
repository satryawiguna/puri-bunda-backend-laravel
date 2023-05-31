<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserLog extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_logs';

    protected $guarded = ['deleted_at'];

    protected $keyType = 'string';

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::creating(function ($contact) {
            $contact->id = Str::uuid(36);
        });
    }
}
