<?php

namespace Modules\Solution\Http\Models;

use Yajra\Pdo;
use Illuminate\Database\Eloquent\Model;


class Issues extends Model 
{
  
  protected $connection = 'app_db';
  protected $table = 'TM_ISSUES';
  protected $primaryKey = 'seq_id';
  public $timestamps = false;

}