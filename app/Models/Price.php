<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
  use SoftDeletes, Uuid;
  protected $keyType = 'string';
  public $incrementing = false;
  protected $fillable = [
    'id',
    'product_id',
    'price',
    'description',
    'due_date',
    'factor',
    'user_id',
    'is_predetermined'
  ];

  protected static function boot()
  {
    parent::boot();

    static::updating(function ($model) {
      $model->user_id = \Auth::user()->id;
    });

    static::creating(function ($model) {
      $model->user_id = \Auth::user()->id;
    });

  }
}
