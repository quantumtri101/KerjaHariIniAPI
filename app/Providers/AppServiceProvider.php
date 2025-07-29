<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use View;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

use App\Http\Controllers\Helper\SidebarHelper;
use App\Http\Controllers\BaseController;

use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
  /**
  * Register any application services.
  *
  * @return void
  */
  public function register()
  {
    //
  }

  /**
  * Bootstrap any application services.
  *
  * @return void
  */
  public function boot()
  {
    $base = new BaseController();
    view()->composer('*', function ($view) {
      $sidebar_helper = new SidebarHelper();
      $base = new BaseController();
      // View::share('arr_sidebar', $sidebar_helper->get_arr_sidebar($this->app->request));
      // View::share('column_per_business', 'category_id');
      // View::share('web_admin_name', $base->web_admin_name);
      // View::share('app_version', $base->app_version);
      // View::share('job_wait_time', $base->job_wait_time);
      // View::share('url_asset', url('/'));

      $view->with([
        'arr_sidebar' => $sidebar_helper->get_arr_sidebar($this->app->request),
        'column_per_business' => 'category_id',
        'web_admin_name' => $base->web_admin_name,
        'app_version' => $base->app_version,
        'job_wait_time' => $base->job_wait_time,
        'url_asset' => $base->url_asset,
      ]);
    });
    App::setLocale($base->locale);

    Paginator::useBootstrap();
  }
}
