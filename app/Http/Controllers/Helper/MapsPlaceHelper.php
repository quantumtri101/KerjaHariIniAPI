<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use Auth;
use Curl;

use App\Http\Controllers\Helper\IDHelper;
use App\Http\Controllers\Helper\CurlHelper;

use App\Models\CurlLog;

class MapsPlaceHelper{
  public $id_helper;
  public $api_key = 'AIzaSyDIhtsbpXsgVpDxfV1n0uMsjnK6bMFe7dI';
  public $url_maps = "https://maps.googleapis.com/maps/api";

  public function __construct(){
    // $this->base = new BaseController();
    $this->id_helper = new IDHelper();
  }

  public function get_place_detail($place_id){
    $curl_helper = new CurlHelper();

    $response = $curl_helper->request($this->url_maps.'/place/details/json?place_id='.$place_id.'&key='.$this->api_key, [], [], 'get');
    dd($response);
  }

  public function get_place_search($query){
    $curl_helper = new CurlHelper();

    $response = $curl_helper->request($this->url_maps.'/place/textsearch/json?query='.str_replace(' ', '%20', $query).'&key='.$this->api_key, [], [], 'get');

    $arr = [];
    if(!empty($response) && count($response["results"]) > 0)
      $arr = [
        'latitude' => $response["results"][0]["geometry"]["location"]["lat"],
        'longitude' => $response["results"][0]["geometry"]["location"]["lng"],
      ];

    return $arr;
  }

  public function get_location_detail($latitude, $longitude){
    $response = Curl::to($this->url_maps.'/geocode/json?key='.$this->api_key.'&latlng='.$latitude.','.$longitude)
      ->withContentType('application/json')
      ->withTimeout(120)
      ->asJson(true)
      ->get();

    return $response;
  }
}
