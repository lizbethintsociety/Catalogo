<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'code',
    'type_product',
    'relation_product_id',
    'name',
    'description',
    'user_id',
    'unit_id',
    'category_id',
    'is_allow_deleted',
    'is_sale',
  ];

  protected static function boot()
  {
    parent::boot();

    static::updating(function ($model) {
      if (Auth::user()) {
        $model->user_id = \Auth::user()->id;
      }
    });

    static::creating(function ($model) {
      if ($model->relation_product_id == null){
//        $model->relation_product_id = $model->id;
      }
      if (Auth::user()) {
        $model->user_id = \Auth::user()->id;
      }
    });

  }

  function prices() {
    return $this->hasMany(Price::class, 'product_id');
  }

}
