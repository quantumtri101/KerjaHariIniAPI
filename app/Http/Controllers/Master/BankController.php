<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Bank;

class BankController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "bank.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.bank.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new Bank();

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
      $data = Bank::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.bank.action', [
      'bank' => $data,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'master.bank.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_bank = json_decode($request->arr_bank, true);

    foreach($arr_bank as $bank){
      $data = new Bank();
      $data->is_publish = $bank['is_publish'];
      $data->name = $bank['name'];
      $data->save();

      if(!empty($bank["image"]) && $bank["image"] != ""){
        $this->file_helper->manage_image($bank["image"], $data, 'bank');
        $data->save();
      }
      $data->refresh();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/master/bank');
  }

  public function post(Request $request){
    $data = new Bank();
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $data->refresh();
      $this->file_helper->manage_image($request->image, $data, 'bank');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/bank');
  }

  public function put(Request $request){
    $data = Bank::find($request->id);
    $data->name = $request->name;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $data->refresh();
      $this->file_helper->manage_image($request->image, $data, 'bank');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/bank');
  }

  public function delete(Request $request){
    Bank::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/bank');
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
