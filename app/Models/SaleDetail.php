<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'price_unit',
    'quantity',
    'price_total',
    'price_total_end',
    'discount',
    'product_id',
    'service_id',
    'dispatch',
    'delivery_quantity',
    'sale_id',
    'number_order',
    'type_discount',
    'type_sale_detail'
  ];
}
