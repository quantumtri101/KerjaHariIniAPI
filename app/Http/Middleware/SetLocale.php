<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\App;
use View;

use App\Http\Controllers\Helper\LocaleHelper;

class SetLocale
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle(Request $request, Closure $next)
  {
    // dd($request->session()->get('locale'));
    // App::setLocale($request->session()->get('locale', 'en'));
    //
    $locale_helper = new LocaleHelper();
    View::share('current_lang', App::currentLocale());
    View::share('arr_locale', $locale_helper->get_arr_locale());

    return $next($request);
  }
}
