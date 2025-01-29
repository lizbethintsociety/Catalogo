<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSubCategory extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id', 'product_id', 'category_id'
  ];
}
