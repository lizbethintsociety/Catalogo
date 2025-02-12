<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TypeSuit extends Model
{
  use SoftDeletes, Uuid, LogsActivity;
  protected $table = 'type_suits';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'name',
    'branch_id',
    'user_id',
  ];
  protected static $logName = 'type_suit_log';
  protected static $logAttributes  = [
    'id',
    'name',
    'branch_id',
    'user_id',
  ];
  protected static $logOnlyDirty = true;


}
