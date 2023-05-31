<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Contact extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $table = 'contacts';

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

    public function contactable()
    {
        return $this->morphTo();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'contact_positions', 'position_id');
    }
}
