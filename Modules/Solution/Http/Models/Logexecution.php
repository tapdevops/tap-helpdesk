<?php

namespace Modules\Solution\Http\Models;

use Yajra\Pdo;
use Illuminate\Database\Eloquent\Model;


class Logexecution extends Model 
{
  
  protected $connection = 'app_db';
  protected $table = 'TR_LOG_EXECUTION';
  protected $primaryKey = 'seq_id';
  public $timestamps = false;

}