<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Country;

class CountryController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "country.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.country.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new Country();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = Country::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.country.action', [
      'country' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new Country();
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/country');
  }

  public function put(Request $request){
    $data = Country::find($request->id);
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/country');
  }

  public function delete(Request $request){
    Country::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/country');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
