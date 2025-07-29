<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Crypt;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Models\Type;

use App\Jobs\SendEmailAuthJob;

class OperatorController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "user.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
    [
      "id" => "email",
      "column" => "user.email",
      "name" => "general.email",
      "data_type" => "string",
    ],
    [
      "id" => "phone",
      "column" => "user.phone",
      "name" => "general.phone",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->user($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'user.operator.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $type_model = new Type();
    $user = new User();

    $arr = new User();

    $arr = $arr->select($user->get_table_name().'.*')
      ->join($type_model->get_table_name(), $user->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->where($type_model->get_table_name().'.name', 'like', 'operator');

    if(!empty($request->id))
      $arr = $arr->where($user->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($user->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = User::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'user.operator.action', [
      'user' => $data,
    ]);
  }

  public function detail(Request $request){
    $data = User::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'user.operator.detail', [
      'user' => $data,
    ]);
  }

  private function manage_image($image, $index, $user, $column){
    $req = [
      "image" => $image,
    ];
    $this->file_helper->manage_image($req, $user, 'user', $column);
  }

  public function post(Request $request){
    $type = Type::where('name', 'like', 'operator')->first();
    $user = User::where('email','=',$request->email)->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $password = $this->string_helper->generateRandomString($this->str_length);
    $data = new User();
    $data->type_id = $type->id;
    $data->name = $request->name;
    $data->phone = $request->phone;
    $data->email = $request->email;
    $data->password = Hash::make($password);
    $this->manage_image($request->image, 'user', $data, 'file_name');
    $data->encrypt = $encrypt;
    $data->save();

    SendEmailAuthJob::dispatch('email.auth.register', [
      'user' => $data,
      'status' => 'Welcome',
      'url_frontend' => url('/auth/reset-password?encrypt='.$encrypt),
      'type' => 'register',
      'app_name' => $this->app_name,
      'app_address' => $this->app_address,
    ], $data, 'Registration Operator Process Successful')
      ->onQueue('worker_1')
      ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator');
  }

  public function put(Request $request){
    // dd($request->all());
    $user = User::where('email','=',$request->email)
      ->where('id', '!=', $request->id)
      ->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $data = User::find($request->id);
    $data->name = $request->name;
    $data->phone = $request->phone;
    $data->email = $request->email;
    $this->manage_image($request->image, 'user', $data, 'file_name');
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator/detail?id='.$data->id);
  }

  public function delete(Request $request){
    User::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
