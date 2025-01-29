<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id','name', 'description', 'user_id','is_visible',
  ];

  public function sub_categories(){
    return $this->hasMany(SubCategory::class, 'category_id');
  }

  protected static function boot()
  {
    parent::boot();

    static::updating(function ($model) {
      $model->user_id = \Auth::user()->id;
    });

    static::creating(function ($model) {
      if (Auth::user()) {
        $model->user_id = \Auth::user()->id;
      }
    });

  }

}
