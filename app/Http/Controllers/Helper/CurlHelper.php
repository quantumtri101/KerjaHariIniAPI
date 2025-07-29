<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use Auth;
use Curl;

use App\Http\Controllers\Helper\IDHelper;

use App\Models\CurlLog;

class CurlHelper{
  public $id_helper;

  public function __construct(){
    // $this->base = new BaseController();
    $this->id_helper = new IDHelper();
  }

  public function request($url, $arr_header, $arr_data = [], $method = "post"){
    $response = Curl::to($url)
      ->withContentType('application/json')
      ->withHeaders($arr_header)
      ->withData($arr_data);

    if($method == "post")
      $response = $response->asJson(true)->post();
    else if($method == "get")
      $response = $response->asJson(true)->get();
    else if($method == "put")
      $response = $response->asJson(true)->put();

    $this->insert_log($method, $url, $arr_header, $arr_data, $response);

    return $response;
  }

  private function insert_log($method, $url, $arr_header, $arr_data, $response){
    $data = new CurlLog();
    try{
      $data->method = $method;
      $data->url = $url;
      $data->header = json_encode($arr_header);
      $data->request = json_encode($arr_data);
      $data->response = json_encode($response);
      $data->save();
    } catch(Exception $e) {
      $this->insert_log($method, $url, $arr_header, $arr_data, $response);
    }
  }
}
