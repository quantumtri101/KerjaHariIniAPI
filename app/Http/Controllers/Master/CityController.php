<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;

class CityController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "city.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
    [
      "id" => "province_name",
      "column" => "province.name",
      "name" => "general.province_name",
      "data_type" => "string",
    ],
    [
      "id" => "country_name",
      "column" => "country.name",
      "name" => "general.country_name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->text = $data->name;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.city.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $city = new City();
    $province = new Province();
    $country = new Country();

    $arr = new City();
    $arr = $arr->select($city->get_table_name().'.*', $province->get_table_name().'.name as province_name', $country->get_table_name().'.name as country_name')
      ->join($province->get_table_name(), $city->get_table_name().'.province_id', '=', $province->get_table_name().'.id')
      ->join($country->get_table_name(), $province->get_table_name().'.country_id', '=', $country->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($city->get_table_name().'.id', '=', $request->id);

    if(!empty($request->province_id))
      $arr = $arr->where($city->get_table_name().'.province_id', '=', $request->province_id);

    if(!empty($request->name))
      $arr = $arr->where($city->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = City::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.city.action', [
      'city' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new City();
    $data->province_id = $request->province_id;
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/city');
  }

  public function put(Request $request){
    $data = City::find($request->id);
    $data->province_id = $request->province_id;
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/city');
  }

  public function delete(Request $request){
    City::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/city');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->text = $data->name;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
