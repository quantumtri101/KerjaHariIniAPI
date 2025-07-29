<?php
namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\ChatRoom;
use App\Models\Chat;
use App\Models\AssignmentAssessment;
use App\Models\Term;
use App\Models\Order;

use App\Events\NewChatEvent;

class ChatRoomController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "chat.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->date_format = $data->created_at->formatLocalized('%Y-%m-%d %H:%M:%S');
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'chat.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $chat_room_model = new ChatRoom();
    $chat_model = new Chat();

    $arr = ChatRoom::select($chat_room_model->get_table_name().'.*')
      ->join($chat_model->get_table_name(), $chat_room_model->get_table_name().'.chat_id', '=', $chat_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($chat_room_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->chat_id))
      $arr = $arr->where($chat_room_model->get_table_name().'.chat_id', '=', $request->chat_id);

    if(!empty($request->assignment_submitted_id))
      $arr = $arr->where($chat_model->get_table_name().'.assignment_submitted_id', '=', $request->assignment_submitted_id);

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('created_at', 'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    if(!empty($request->chat_id))
      $chat = Chat::find($request->chat_id);
    else{
      $order = Order::find($request->order_id);
      $other_person = $order->user->id == Auth::user()->id ? $order->theraphyst : $order->user;

      $chat = new Chat();
      $chat->person_1_id = Auth::user()->id;
      $chat->person_2_id = $other_person->id;
      $chat->order_id = $order->id;
      $chat->save();
    }
    $receiver = $chat->person_1->id == Auth::user()->id ? $chat->person_2 : $chat->person_1;

    $data = new ChatRoom();
    $data->chat_id = $chat->id;
    $data->sender_id = Auth::user()->id;
    $data->receiver_id = $receiver->id;
    $data->message = $request->message;
    $data->save();

    $data->refresh();
    if(!empty($request->image))
      $this->file_helper->manage_image($request->image, $data, 'chat');
    $data->save();

    NewChatEvent::dispatch($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/chat');
  }

  public function set_read(Request $request){
    $arr_chat_room = ChatRoom::where('chat_id', '=', $request->chat['id'])
      ->where('receiver_id', '=', Auth::user()->id)
      ->whereNull('read_at')
      ->get();
    foreach($arr_chat_room as $chat_room){
      $chat_room->read_at = Carbon::now();
      $chat_room->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/chat');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
