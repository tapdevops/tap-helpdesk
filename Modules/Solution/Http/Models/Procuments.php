<?php

namespace Modules\Solution\Http\Models;

use Yajra\Pdo;
use Illuminate\Database\Eloquent\Model;


class Procurements extends Model 
{
  

  function __construct($connection)
  {
    $this->connect($connection);
  }


}