<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\App;
use View;

class ForceLogin
{
  /**
  * Get the path the user should be redirected to when they are not authenticated.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return string|null
  */
  public function handle(Request $request, Closure $next)
  {
    if(Auth::check() && Auth::user()->session_id != $request->session()->getId()){
      Auth::logout();
      return redirect('/auth/login');
    }

    return $next($request);
  }
}
