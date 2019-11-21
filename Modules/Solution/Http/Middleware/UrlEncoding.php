<?php

namespace Modules\Solution\Http\Middleware;

use Closure;
use DB;
use Session;
use URL;
/**
 *
 */
class UrlEncode
{
  public function handle($request, Closure $next){
    // echo "yaddi";
    $request->username = 'yadd;';

    return $next($request);
     
  }
}
 ?>
