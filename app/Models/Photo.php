<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'photo',
    'name',
    'cover',
    'product_id',
    'user_id'
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
