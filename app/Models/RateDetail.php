<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateDetail extends Model
{
  use SoftDeletes, Uuid;
  protected $table = 'rates_details';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'time_start',
    'price',
    'time_end',
    'rate_id',
    'user_id'
  ];
}
