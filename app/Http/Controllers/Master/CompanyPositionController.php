<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\CompanyPosition;
use App\Models\Type;
use App\Models\User;

class CompanyPositionController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "company_position.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $staff = User::where('company_position_id', '=', $data->id)->first();
      $data->allow_delete = empty($staff);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.company_position.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new CompanyPosition();

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
      $data = CompanyPosition::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.company_position.action', [
      'company_position' => $data,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'master.company_position.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_company_position = json_decode($request->arr_company_position, true);

    foreach($arr_company_position as $company_position){
      $data = new CompanyPosition();
      $data->name = $company_position['name'];
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/master/company-position');
  }

  public function post(Request $request){
    $data = new CompanyPosition();
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company-position');
  }

  public function put(Request $request){
    $data = CompanyPosition::find($request->id);
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company-position');
  }

  public function delete(Request $request){
    CompanyPosition::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company-position');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
