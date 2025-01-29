<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
  use SoftDeletes, Uuid;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'code',
    'batch_id',
    'payment_type',
    'amount_total',
    'user_id',
    'is_paid',
    'next_payment_date',
    'days',
    'money_fine'
  ];
}
