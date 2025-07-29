<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\MapsPlaceHelper;

class MapsController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "order.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function get_location_detail(Request $request){
    $maps_helper = new MapsPlaceHelper();
    $response = $maps_helper->get_location_detail($request->latitude, $request->longitude);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($response["results"]) && count($response["results"]) > 0 ? $response["results"][0]["formatted_address"] : "",
    ]);
  }
}
