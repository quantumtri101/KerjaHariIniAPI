<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Crypt;
use Auth;
use Excel;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Models\Type;
use App\Models\JobsApplication;
use App\Models\Company;
use App\Models\CompanyPosition;

use App\Jobs\SendEmailAuthJob;

use App\Exports\UserRegularExport;

use App\Imports\UserRegularImport;
class CustomerRegularController extends BaseController{
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
    ], 'view', 'user.customer.regular.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $type_model = new Type();
    $company_model = new Company();
    $company_position_model = new CompanyPosition();
    $user = new User();

    $arr = new User();

    $arr = $arr->select($user->get_table_name().'.*', $user->get_table_name().'.created_at as created_at_format', $user->get_table_name().'.updated_at as updated_at_format', $company_model->get_table_name().'.name as company_name',)
      ->join($type_model->get_table_name(), $user->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->leftJoin($company_model->get_table_name(), $user->get_table_name().'.company_id', '=', $company_model->get_table_name().'.id')
      ->leftJoin($company_position_model->get_table_name(), $user->get_table_name().'.company_position_id', '=', $company_position_model->get_table_name().'.id')
      ->where($type_model->get_table_name().'.name', 'like', 'customer_regular');

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
    $data = null;
    $arr_company = Company::all();
    if(!empty($request->id)){
      $data = User::find($request->id);
      $data->phone = str_replace("+62", "", $data->phone);
    }

    return $this->get_data_helper->return_data($request, [], 'view', 'user.customer.regular.action', [
      'user' => $data,
      'arr_company' => $arr_company,
    ]);
  }

  public function import(Request $request){
    Excel::import(new UserRegularImport, $request->export);

    return $this->get_data_helper->return_data($request, [], 'view', 'user.customer.regular.index');
  }

  public function export(Request $request){
    $arr_user = [];
    if(!empty(Auth::user()->company))
      $arr_user = User::where('company_id', '=', Auth::user()->company->id)->get();
    else
      $arr_user = User::all();
    return Excel::download(new UserRegularExport($arr_user), 'customer_regular.xlsx');
  }

  public function detail(Request $request){
    $jobs_application = null;
    if(!empty($request->jobs_application_id)){
      $jobs_application = JobsApplication::find($request->jobs_application_id);
      $this->relationship_helper->jobs_application($jobs_application);
    }
    $data = User::find($request->id);
    $data->phone = str_replace("+62", "", $data->phone);

    $arr_tab = [];
    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "user.customer.regular.component.general_info",
      ],
      // [
      //   "id" => "history_salary",
      //   "component" => "user.customer.regular.component.list_salary",
      // ],
      // [
      //   "id" => "history_request_withdraw",
      //   "component" => "user.customer.regular.component.list_request_withdraw",
      // ],
      // [
      //   "id" => "list_jobs",
      //   "component" => "user.customer.regular.component.list_jobs",
      // ],
      // [
      //   "id" => "list_recommendation",
      //   "component" => "user.customer.regular.component.list_jobs_recommendation",
      // ],
    ];

    if(empty($jobs_application))
      array_push($arr_tab, [
        "id" => "history_salary",
        "component" => "user.customer.regular.component.list_salary",
      ],[
        "id" => "list_jobs",
        "component" => "user.customer.regular.component.list_jobs",
      ],);

    return $this->get_data_helper->return_data($request, [], 'view', 'user.customer.regular.detail', [
      'customer_regular' => $data,
      'arr_tab' => $arr_tab,
      'jobs_application' => $jobs_application,
    ]);
  }

  private function manage_image($image, $index, $user, $column){
    $req = $image;
    $this->file_helper->manage_image($req, $user, 'user', $column);
  }

  public function post(Request $request){
    $type = Type::where('name', 'like', 'customer_regular')->first();
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
    $data->name = $request->name;
    $data->phone = "+62".$request->phone;
    $data->email = strtolower($request->email);
    $data->is_active = $request->is_active;
    $data->gender = $request->gender;
    $data->password = Hash::make($password);
    $data->contract_start_date = Carbon::createFromFormat('d/m/Y', $request->contract_start_date);
    $data->contract_duration = $request->contract_duration;
    $data->id_no = $request->id_no;
    $data->save();

    if(!empty($request->image)){
      $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
      $data->save();
    }

    if(!empty($request->vaccine_covid_image)){
      $this->file_helper->manage_image($request->vaccine_covid_image, $data, 'user_vaccine_covid', 'vaccine_covid_file_name');
      $data->save();
    }

    if(!empty($request->cv_image)){
      $this->file_helper->manage_image($request->cv_image, $data, 'user_cv', 'cv_file_name');
      $data->save();
    }

    SendEmailAuthJob::dispatch('email.auth.register', [
      'user' => $data,
      'status' => 'Welcome',
      'url_frontend' => '',
      'type' => 'register',
      'app_name' => $this->app_name,
      'password' => $password,
    ], $data, 'Registration Staff Regular Process Successful')
      ->onQueue('worker_1')
      ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/regular');
  }

  public function put(Request $request){
    // dd($request->all());
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
    $data->company_id = !empty(Auth::user()->company) ? Auth::user()->company->id : $request->company_id;
    $data->name = $request->name;
    $data->phone = "+62".$request->phone;
    $data->email = strtolower($request->email);
    $data->is_active = $request->is_active;
    $data->gender = $request->gender;
    $data->contract_start_date = Carbon::createFromFormat('d/m/Y', $request->contract_start_date);
    $data->contract_duration = $request->contract_duration;
    $data->id_no = $request->id_no;
    $data->save();

    if(!empty($request->image)){
      $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
      $data->save();
    }

    if(!empty($request->vaccine_covid_image)){
      $this->file_helper->manage_image($request->vaccine_covid_image, $data, 'user_vaccine_covid', 'vaccine_covid_file_name');
      $data->save();
    }

    if(!empty($request->cv_image)){
      $this->file_helper->manage_image($request->cv_image, $data, 'user_cv', 'cv_file_name');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/regular/detail?id='.$data->id);
  }

  public function delete(Request $request){
    User::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/regular');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
