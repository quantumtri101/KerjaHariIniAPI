<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Hash;
use Crypt;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\StaffHelper;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyPosition;
use App\Models\SubCategory;
use App\Models\Jobs;
use App\Models\Type;

use App\Jobs\SendEmailAuthJob;

class StaffController extends BaseController{
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
    ], 'view', 'user.staff.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $type_model = new Type();
    $user = new User();
    $company_model = new Company();
    $company_position_model = new CompanyPosition();
    $sub_category_model = new SubCategory();

    $arr = new User();

    $arr = $arr->select($user->get_table_name().'.*', $user->get_table_name().'.created_at as created_at_format', $user->get_table_name().'.updated_at as updated_at_format', $company_model->get_table_name().'.name as company_name', $company_position_model->get_table_name().'.name as company_position_name', $sub_category_model->get_table_name().'.name as sub_category_name',)
      ->join($type_model->get_table_name(), $user->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->join($company_model->get_table_name(), $user->get_table_name().'.company_id', '=', $company_model->get_table_name().'.id')
      ->join($company_position_model->get_table_name(), $user->get_table_name().'.company_position_id', '=', $company_position_model->get_table_name().'.id')
      ->leftJoin($sub_category_model->get_table_name(), $user->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
      ->where($type_model->get_table_name().'.name', 'like', 'staff');

    if(!empty($request->id))
      $arr = $arr->where($user->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($user->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(empty($request->id) && Auth::check() && !empty(Auth::user()->company))
      $arr = $arr->where($user->get_table_name().'.company_id', '=', Auth::user()->company->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $arr_company = Company::all();
    $arr_company_position = CompanyPosition::all();
    $arr_sub_category = SubCategory::all();
    $data = null;
    if(!empty($request->id)){
      $data = User::find($request->id);
      $data->phone = str_replace("+62", "", $data->phone);
    }

    return $this->get_data_helper->return_data($request, [], 'view', 'user.staff.action', [
      'user' => $data,
      'arr_company' => $arr_company,
      'arr_company_position' => $arr_company_position,
      'arr_sub_category' => $arr_sub_category,
    ]);
  }

  public function detail(Request $request){
    $data = User::find($request->id);
    $data->phone = str_replace("+62", "", $data->phone);

    $arr_tab = [];
    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "user.staff.component.general_info",
      ],
      [
        "id" => "company_info",
        "component" => "user.staff.component.company_info",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'user.staff.detail', [
      'staff' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  private function manage_image($image, $index, $user, $column){
    $req = $image;
    $this->file_helper->manage_image($req, $user, 'user', $column);
  }

  public function post(Request $request){
    $helper = new StaffHelper();
    $type = Type::where('name', 'like', 'staff')->first();
    $user = User::orWhere('phone','=',"+62".$request->phone)->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $password = $this->string_helper->generateRandomString($this->str_length);
    $data = new User();
    $data->type_id = $type->id;
    $data->company_id = !empty(Auth::user()->company) ? Auth::user()->company->id : $request->company_id;
    $data->company_position_id = $request->company_position_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->name = $request->name;
    $data->phone = "+62".$request->phone;
    $data->email = strtolower($request->email);
    $data->is_active = $request->is_active;
    $data->gender = $request->gender;
    $data->password = Hash::make($password);
    $data->save();

    if(!empty($request->image)){
      $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
      $data->save();
    }

    // $helper->add_jobs_approve($data);

    SendEmailAuthJob::dispatch('email.auth.register', [
      'user' => $data,
      'status' => 'Welcome',
      'url_frontend' => '',
      'type' => 'register',
      'app_name' => $this->app_name,
      'password' => $password,
    ], $data, 'Registration Client Process Successful')
      ->onQueue('worker_1')
      ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/staff');
  }

  public function put(Request $request){
    $helper = new StaffHelper();
    $user = User::where(function($where) use($request) {
      $where = $where->orWhere('phone','=',"+62".$request->phone);
    })
      ->where('id', '!=', $request->id)
      ->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $data = User::find($request->id);
    $data->company_position_id = $request->company_position_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->name = $request->name;
    $data->phone = "+62".$request->phone;
    $data->email = strtolower($request->email);
    $data->is_active = $request->is_active;
    $data->gender = $request->gender;
    $data->save();

    if(!empty($request->image)){
      $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
      $data->save();
    }

    // $helper->add_jobs_approve($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/staff/detail?id='.$data->id);
  }

  public function delete(Request $request){
    User::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/staff');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
