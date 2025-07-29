<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

use App\Models\EndpointLog;

use App\Http\Controllers\BaseController;

class CreateLog
{
  public $arr_url_exception = [
  ];
  public $arr_url_include = [
	];
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle(Request $request, Closure $next)
  {
    $flag = $this->check_url_exception($request->path());
    $flag1 = $this->check_url_include($request->path());

    if($request->method() != 'GET' || $flag || $flag1)
      $endpoint = $this->insert_log($request);

    $response = $next($request);

    if(!$flag || $flag1){
      if($response->status() != 200 && $response->status() != 404)
        $endpoint = $this->insert_log($request);

      if(!empty($endpoint)){
        // $endpoint->status_code = $response->status();
        if(Auth::check())
          $endpoint->user_id = Auth::user()->id;
        if(!empty($response->original))
          $endpoint->response = json_encode($response->original);
        $endpoint->save();
      }
    }

    return $response;
  }

  private function check_url_exception($path){
    $flag = false;
    foreach($this->arr_url_exception as $url){
      if($path == $url){
        $flag = true;
        break;
      }
    }
    return $flag;
  }

  private function check_url_include($path){
		$flag = false;
		foreach($this->arr_url_include as $url){
			if(preg_match($url, $path) == 1){
				$flag = true;
				break;
			}
		}
		return $flag;
	}

  private function remove_image_data($obj){
    foreach($obj as $key => $data){
      if(is_array($data))
        $obj[$key] = $this->remove_image_data($data);
      if($key == 'image_data')
        $obj[$key] = null;
    }
    return $obj;
  }

  private function insert_log($request){
    $base = new BaseController();

    $endpoint = new EndpointLog();
    try{
      $arr = $this->remove_image_data($request->all());

      $endpoint->ip = $request->ip();
      $endpoint->method = $request->method();
      $endpoint->url = '/'.$request->path();
      $endpoint->header = json_encode($request->header());
      $endpoint->request = json_encode($arr);
      $endpoint->id = $base->id_helper->generate_new_id_with_date('LOG',new EndpointLog());
      $endpoint->save();
    } catch(\Illuminate\Database\QueryException $e) {
      $endpoint = $this->insert_log($request);
    }
    return $endpoint;
  }
}
