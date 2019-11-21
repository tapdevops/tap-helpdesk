<?php

namespace Modules\Solution\Http\Models;

use Yajra\Pdo;
use Illuminate\Database\Eloquent\Model;


class Solutions extends Model 
{
  
  protected $connection = 'app_db';
  protected $table = 'TM_SOLUTIONS';
  protected $primaryKey = 'seq_id';
  public $timestamps = false;

}