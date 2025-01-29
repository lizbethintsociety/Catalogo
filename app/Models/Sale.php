<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
  use SoftDeletes, Uuid;
  protected $keyType = 'string';
  public $incrementing = false;
  protected $fillable = [
    'id',
    'code',
    'sale_note',
    'type_sale',
    'payment_type',
    'price_initial',
    'price_end',
    'date_sale',
    'amount_total',
    'is_paid',
    'dispatch',
    'is_invoiced',
    'dollar_exchange',
    'discount_total_in_sale',
    'type_discount',
    'branch_id',
    'contract_id',
    'client_id',
    'user_id',
    'checkout_id',
    'invoice_id',
    'sub_client_id',
    'quotation_responsible_user_id',
    'quotation_status',
  ];
}