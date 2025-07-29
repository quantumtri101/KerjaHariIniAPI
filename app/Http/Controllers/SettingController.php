<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Setting;

class SettingController extends BaseController{
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

    $conversion_point = null;
    $transfer_fee = null;
    $top_up_xp = null;
    $pay_bill_xp = null;
    $reserve_table_xp = null;
    $lowest_top_up_bonus = null;
    foreach($arr as $data){
      if($data->key == "conversion_point")
        $conversion_point = $data;
      else if($data->key == "transfer_fee")
        $transfer_fee = $data;
      else if($data->key == "lowest_top_up_bonus")
        $lowest_top_up_bonus = $data;
      else if($data->key == "top_up_xp")
        $top_up_xp = $data;
      else if($data->key == "pay_bill_xp")
        $pay_bill_xp = $data;
      else if($data->key == "reserve_table_xp")
        $reserve_table_xp = $data;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || !empty($request->key) ? $arr[0] : $arr,
    ], 'view', 'setting.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
      'conversion_point' => $conversion_point,
      'transfer_fee' => $transfer_fee,
      'lowest_top_up_bonus' => $lowest_top_up_bonus,
      'top_up_xp' => $top_up_xp,
      'pay_bill_xp' => $pay_bill_xp,
      'reserve_table_xp' => $reserve_table_xp,
    ]);
  }

  public function index_xp(Request $request){
    $arr = $this->manage_where($request);

    $conversion_point = null;
    $transfer_fee = null;
    $top_up_xp = null;
    $pay_bill_xp = null;
    $reserve_table_xp = null;
    foreach($arr as $data){
      if($data->key == "conversion_point")
        $conversion_point = $data;
      else if($data->key == "transfer_fee")
        $transfer_fee = $data;
      else if($data->key == "top_up_xp")
        $top_up_xp = $data;
      else if($data->key == "pay_bill_xp")
        $pay_bill_xp = $data;
      else if($data->key == "reserve_table_xp")
        $reserve_table_xp = $data;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'setting.index_xp', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
      'conversion_point' => $conversion_point,
      'transfer_fee' => $transfer_fee,
      'top_up_xp' => $top_up_xp,
      'pay_bill_xp' => $pay_bill_xp,
      'reserve_table_xp' => $reserve_table_xp,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new Setting();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->key))
      $arr = $arr->where('key', 'like', $request->key);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function put(Request $request){
    $data = Setting::where('key', '=', $request->key)->first();
    $data->value = str_replace(',', '.', str_replace('.', '', $request->value));
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
