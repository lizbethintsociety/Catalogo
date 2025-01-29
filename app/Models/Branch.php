<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
  use SoftDeletes, Uuid;

  protected $table = 'branch';
  protected $keyType = 'string';
  public $incrementing = false;
  protected $fillable = [
    'id',
    'name',
    'code',
    'address' ,
    'phone',
    'phone_alternative',
    'is_main',
    'is_warehouse',
    'key_dosing',
    'control_code',
    'date_dosing',
    'date_end_dosing',
    'allow_billing',
    'city',
    'issued_at',
    'user_id',
  ];
}
