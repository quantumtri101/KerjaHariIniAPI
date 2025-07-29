<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Hash;
use Crypt;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\CustomerOncallHelper;

use App\Models\User;
use App\Models\JobsApplication;
use App\Models\Jobs;
use App\Models\Rating;
use App\Models\Type;

use App\Jobs\SendEmailAuthJob;

class CustomerOncallController extends BaseController{
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
      $this->relationship_helper->user($data, $request);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'user.customer.oncall.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $type_model = new Type();
    $jobs_application_model = new JobsApplication();
    $jobs_model = new Jobs();
    $user = new User();

    $arr = new User();

    $arr = $arr->select($user->get_table_name().'.*', $user->get_table_name().'.created_at as created_at_format', $user->get_table_name().'.updated_at as updated_at_format')
      ->join($type_model->get_table_name(), $user->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->where($type_model->get_table_name().'.name', 'like', 'customer_oncall');

    if(!empty($request->id))
      $arr = $arr->where($user->get_table_name().'.id', '=', $request->id);

    if(!empty(Auth::user()->company)){
      $jobs_temp = Jobs::select('company_id')
        ->selectRaw('MAX(id) as id')
        ->groupBy('company_id');

      $jobs_temp1 = Jobs::select($jobs_model->get_table_name().'.*',)
        ->joinSub($jobs_temp, 'temp', 'temp.id', '=', $jobs_model->get_table_name().'.id');

      $application_temp = JobsApplication::select('user_id')
        ->selectRaw('MAX(id) as id')
        ->groupBy('user_id');

      $application_temp1 = JobsApplication::select($jobs_application_model->get_table_name().'.*',)
        ->joinSub($application_temp, 'temp', 'temp.id', '=', $jobs_application_model->get_table_name().'.id');

      

      $arr = $arr->joinSub($application_temp1, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user->get_table_name().'.id')
        ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id);
    }
    // dd($arr->toSql());

    if(!empty($request->name))
      $arr = $arr->where($user->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id)){
      $data = User::find($request->id);
      $data->phone = str_replace("+62", "", $data->phone);
    }

    $arr_marital_status = [
      [
        "id" => "unmarried",
      ],
      [
        "id" => "married",
      ],
      [
        "id" => "divorce_by_death",
      ],
      [
        "id" => "divorce_by_living",
      ],
    ];
    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "user.customer.oncall.component.action.general_info",
      ],
      [
        "id" => "resume_info",
        "component" => "user.customer.oncall.component.action.resume_info",
      ],
      [
        "id" => "experience_info",
        "component" => "user.customer.oncall.component.action.experience_info",
      ],
      [
        "id" => "skill_info",
        "component" => "user.customer.oncall.component.action.skill_info",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'user.customer.oncall.action', [
      'user' => $data,
      'arr_tab' => $arr_tab,
      'arr_marital_status' => $arr_marital_status,
    ]);
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
        "component" => "user.customer.oncall.component.general_info",
      ],
      
    ];
    if(empty($jobs_application))
      array_push($arr_tab, 
        [
          "id" => "history_salary",
          "component" => "user.customer.oncall.component.list_salary",
        ],
        [
          "id" => "history_request_withdraw",
          "component" => "user.customer.oncall.component.list_request_withdraw",
        ],
        [
          "id" => "list_jobs",
          "component" => "user.customer.oncall.component.list_jobs",
        ],
        [
          "id" => "list_recommendation",
          "component" => "user.customer.oncall.component.list_jobs_recommendation",
        ],
        [
          "id" => "list_rating",
          "component" => "user.customer.oncall.component.list_rating",
        ],
      );
    if(count($data->resume) > 0)
      array_push($arr_tab, [
        "id" => "resume_data",
        "component" => "user.customer.oncall.component.resume_data",
      ],);

    if(count($data->general_quiz_result) > 0)
      array_push($arr_tab, [
        "id" => "list_general_quiz_answer",
        "component" => "user.customer.oncall.component.list_general_quiz_answer",
      ]);

    return $this->get_data_helper->return_data($request, [], 'view', 'user.customer.oncall.detail', [
      'customer_oncall' => $data,
      'arr_tab' => $arr_tab,
      'jobs_application' => $jobs_application,
    ]);
  }

  private function manage_image($image, $index, $user, $column){
    $req = $image;
    $this->file_helper->manage_image($req, $user, 'user', $column);
  }

  public function post(Request $request){
    $helper = new CustomerOncallHelper();
    $type = Type::where('name', 'like', 'customer_oncall')->first();
    $user = User::orWhere('phone','=',"+62".$request->phone)->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $password = $this->string_helper->generateRandomString($this->str_length);
    $data = new User();
    $data->type_id = $type->id;
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

    $helper->edit_resume($request, $data);

    SendEmailAuthJob::dispatch('email.auth.register', [
      'user' => $data,
      'status' => 'Welcome',
      'url_frontend' => '',
      'type' => 'register',
      'app_name' => $this->app_name,
      'password' => $password,
    ], $data, 'Registration Staff Oncall Process Successful')
      ->onQueue('worker_1')
      ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/oncall');
  }

  public function put(Request $request){
    $helper = new CustomerOncallHelper();

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

    $helper->edit_resume($request, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/oncall/detail?id='.$data->id);
  }

  public function delete(Request $request){
    User::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/oncall');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
