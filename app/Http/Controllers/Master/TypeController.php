<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\Type;

class TypeController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "type.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->text = __('general.'.$data->name);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new Type();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->type)){
      if($request->type == "admin")
        $arr = $arr->where(function($where) {
          if(Auth::user()->type->name == "super_admin_resto")
            $where = $where->orWhere('name', '=', 'admin_cabang_resto')
              ->orWhere('name', '=', 'cashier')
              ->orWhere('name', '=', 'marketing')
              ->orWhere('name', '=', 'accounting');
          else if(Auth::user()->type->name == "admin_cabang_resto")
            $where = $where->orWhere('name', '=', 'cashier')
              ->orWhere('name', '=', 'marketing')
              ->orWhere('name', '=', 'accounting');
          else
            $where = $where->orWhere('name', '=', 'admin_cabang_resto')
              ->orWhere('name', '=', 'cashier')
              ->orWhere('name', '=', 'marketing')
              ->orWhere('name', '=', 'accounting');
        });
    }

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->text = __('general.'.$data->name);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
