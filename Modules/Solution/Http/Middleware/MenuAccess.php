<?php

namespace Modules\Solution\Http\Middleware;

use Closure;
use Session;
use DB;
use URL;
/**
 *
 */
class MenuAccess
{
  public function handle($request, Closure $next, $path) {

    $db = DB::connection('app_db');
    $request->session()->put('current_path', $path);
    return $next($request);
  }

}
 ?>
