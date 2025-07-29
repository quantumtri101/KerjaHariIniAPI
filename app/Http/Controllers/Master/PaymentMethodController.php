<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\PaymentMethod;

class PaymentMethodController extends BaseController{
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

    foreach($arr as $data){
      $data->text = $data->name;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.country.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  public function index_group(Request $request){
    $arr = $this->manage_where($request, 'all');

    $arr_group = [];
    foreach($arr as $data){
      $flag = false;
      foreach($arr_group as $group){
        if($group["id"] == $data->data){
          $flag = true;
          break;
        }
      }
      if(!$flag)
        array_push($arr_group, [
          "id" => $data->data,
          "name" => __('general.'.$data->data),
          "payment_method" => [],
        ]);
    }
    foreach($arr as $data){
      foreach($arr_group as $key => $group){
        if($group["id"] == $data->data)
          array_push($arr_group[$key]["payment_method"], $data);
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $arr_group,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new PaymentMethod();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->data))
      $arr = $arr->where('data', 'like', $request->data);

    if(!empty($request->is_active))
      $arr = $arr->where('is_active', 'like', $request->is_active);

    if(!empty($request->type)){
      if($request->type == "via_xendit")
        $arr = $arr->where('data', 'not like', 'cash');
    }

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
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
