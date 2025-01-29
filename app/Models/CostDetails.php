<?php

namespace App\Models;

use Carbon\Carbon;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostDetails extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'cost_unit',
    'quantity',
    'batch_id',
    'sale_detail_id',
    'created_at'
  ];


}
