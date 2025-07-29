<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Province;
use App\Models\Country;

class ProvinceController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "province.name",
      "name" => "general.name",
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

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.province.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $province = new Province();
    $country = new Country();

    $arr = new Province();

    $arr = $arr->select($province->get_table_name().'.*', $country->get_table_name().'.name as country_name')
      ->join($country->get_table_name(), $province->get_table_name().'.country_id', '=', $country->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->country_id))
      $arr = $arr->where('country_id', '=', $request->country_id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = Province::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.province.action', [
      'province' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new Province();
    $data->country_id = $request->country_id;
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/province');
  }

  public function put(Request $request){
    $data = Province::find($request->id);
    $data->country_id = $request->country_id;
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/province');
  }

  public function delete(Request $request){
    Province::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/province');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
