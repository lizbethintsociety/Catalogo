<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Suit extends Model
{
  use SoftDeletes, Uuid, LogsActivity;
  protected $table = 'suits';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'name',
    'description',
    'status',
    'audio',
    'branch_id',
    'type_suit_id',
    'status_id',
    'user_id',
    'deviceID_suit',
    'deviceID_tina',
    'user_clean_id',
    'user_check_id',
  ];
  protected static $logName = 'suit_log';
  protected static $logAttributes  = [
    'id',
    'name',
    'description',
    'status',
    'audio',
    'branch_id',
    'type_suit_id',
    'status_id',
    'user_id',
    'deviceID_suit',
    'deviceID_tina',
    'user_check_id',
    'user_clean_id',
  ];
  protected static $logOnlyDirty = true;

}
