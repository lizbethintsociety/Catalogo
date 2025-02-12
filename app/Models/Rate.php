<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'name',
    'is_promo',
    'extra_hour',
    'date_start',
    'date_end',
    'type_suit_id',
    'combo_product_id',
    'branch_id',
    'user_id'
  ];
}
