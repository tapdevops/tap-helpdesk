<?php

namespace Modules\Solution\Http\Middleware;

use Closure;
use DB;
use Session;
use URL;
/**
 *
 */
class CekLogin
{
  public function handle($request, Closure $next){
    if (Session::get('is_logged_in') != null) {
      return $next($request);
    }else {
      Session::flush();
      Session::set('redirect_to', URL::current());
      $url = URL::to('/solution/login');
      return Redirect($url);
    }    
  }
}
 ?>
