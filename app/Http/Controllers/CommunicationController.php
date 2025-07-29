<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;

use App\Models\Communication;
use App\Models\CommunicationType;
use App\Models\CommunicationMethod;
use App\Models\Outlet;
use App\Models\Type;
use App\Models\User;

use App\Jobs\SendCommunicationJob;

class CommunicationController extends BaseController{
  private $arr_header = [
    [
      "id" => "subject",
      "column" => "communication.subject",
      "name" => "general.subject",
      "data_type" => "string",
    ],
    [
      "id" => "type_name",
      "column" => "communication_type.name",
      "name" => "general.type_name",
      "data_type" => "string",
    ],
    [
      "id" => "method_name",
      "column" => "communication_method.name",
      "name" => "general.method_name",
      "data_type" => "string",
    ],
    [
      "id" => "created_at",
      "column" => "communication_method.created_at",
      "name" => "general.created",
      "data_type" => "date_only",
    ],
    [
      "id" => "sent_at",
      "column" => "communication_method.sent_at",
      "name" => "general.sent",
      "data_type" => "date_only",
    ],
  ];
  public function get_header(){ return $this->arr_header; }

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $key => $data){
      $this->relationship_helper->communication($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'notification.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  public function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $communication_model = new Communication();
    $outlet_model = new Outlet();
    $type_model = new Type();

    $arr = new Communication();
    $arr = $arr->select($communication_model->get_table_name().'.*', $communication_model->get_table_name().'.scheduled_at as scheduled_at_format')
      ->selectRaw('CONCAT('.$outlet_model->get_table_name().'.name, ", ", '.$type_model->get_table_name().'.name) as sent_to')
      ->join($outlet_model->get_table_name(), $communication_model->get_table_name().'.outlet_id', '=', $outlet_model->get_table_name().'.id')
      ->join($type_model->get_table_name(), $communication_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($communication_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->status))
      $arr = $arr->where($communication_model->get_table_name().'.status', '=', $request->status);

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('created_at' ,'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function test_send_notification(Request $request){
    $helper = new CommunicationHelper();

    $response = $helper->send_test_push_notif($request->token, $request->title, $request->body, $request->payload);

    // SendCommunicationJob::dispatch($data)
    //   ->onQueue('worker_2')
    //   ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => !empty($response["status"]) && $response["status"] == "success" ? 'success' : 'error',
      'data' => $response,
    ], 'redirect', '/notification');
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = Communication::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'notification.action', [
      'notification' => $data,
    ]);
  }

  public function detail(Request $request){
    $data = Communication::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'notification.detail', [
      'notification' => $data,
    ]);
  }

  public function post(Request $request){
    $outlet = Outlet::find($request->outlet_id);
    $type = Type::find($request->type_id);

    $data = new Communication();
    $data->outlet_id = $outlet->id;
    $data->type_id = $type->id;
    $data->title = $request->title;
    $data->detail = $request->detail;
    $data->scheduled_at = Carbon::createFromFormat('d/m/Y H:i', $request->scheduled_at);
    $data->save();

    // SendCommunicationJob::dispatch($data)
    //   ->onQueue('worker_2')
    //   ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/notification');
  }

  public function put(Request $request){
    $outlet = Outlet::find($request->outlet_id);
    $type = Type::find($request->type_id);

    $data = Communication::find($request->id);
    $data->outlet_id = $outlet->id;
    $data->type_id = $type->id;
    $data->title = $request->title;
    $data->detail = $request->detail;
    $data->scheduled_at = Carbon::createFromFormat('d/m/Y H:i', $request->scheduled_at);
    $data->save();

    // SendCommunicationJob::dispatch($data)
    //   ->onQueue('worker_2')
    //   ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/notification/detail?id='.$request->id);
  }

  public function delete(Request $request){
    $data = Communication::find($request->id);
    $data->status = 'canceled';
    $data->save();

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
