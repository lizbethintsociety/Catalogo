<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'is_by_time',
        'is_many',
        'active_image_url',
        'icon',
        'image_url',
        'deviceID',
        'is_tina',
        'rate_id',
        'branch_id',
        'user_id'
    ];
}
