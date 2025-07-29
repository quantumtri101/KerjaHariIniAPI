<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\Notification;

class NotificationController extends BaseController{
  private $arr_header = [

  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'notification.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new Notification();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(empty($request->id) && (Auth::user()->type->name == 'customer_regular' || Auth::user()->type->name == 'customer_oncall'))
      $arr = $arr->where('user_id', '=', Auth::user()->id);

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('created_at', 'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function get_total_unread(Request $request){
    $total_unread = Notification::selectRaw('COUNT(id) as total')
      ->where('user_id', '=', Auth::user()->id)
      ->whereNull('read_at')
      ->first();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $total_unread->total,
    ]);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = Notification::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'notification.detail', [
      'notification' => $data,
    ]);
  }

  public function set_read(Request $request){
    $arr = Notification::where('user_id', '=', Auth::user()->id)->get();
    foreach($arr as $data){
      $data->read_at = Carbon::now();
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/notification');
  }

  public function delete(Request $request){
    Notification::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/notification');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
