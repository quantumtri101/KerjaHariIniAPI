<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsApplicationSalaryHelper;

use App\Models\JobsApplication;
use App\Models\JobsApplied;
use App\Models\JobsSalary;
use App\Models\JobsInterview;
use App\Models\Jobs;
use App\Models\Resume;
use App\Models\GeneralQuizResult;
use App\Models\User;
use App\Models\Type;

class JobsApplicationController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_application.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data)
      $this->relationship_helper->jobs_application($data, $request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs_application.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_application_model = new JobsApplication();
    $user_model = new User();
    $type_model = new Type();
    $resume_model = new Resume();

    $temp = Resume::select('user_id')
      ->selectRaw('MAX(id) as id')
      ->groupBy('user_id');

    $temp1 = Resume::select($resume_model->get_table_name().'.*')
      ->joinSub($temp, 'temp', $resume_model->get_table_name().'.id', '=', 'temp.id');

    $arr = JobsApplication::select($jobs_application_model->get_table_name().'.*', $user_model->get_table_name().'.name as user_name', $user_model->get_table_name().'.gender as gender_format', $user_model->get_table_name().'.id_no as id_no', $type_model->get_table_name().'.name as type_name_format')
      ->leftJoin($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->leftJoin($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->leftJoinSub($temp1, $resume_model->get_table_name(), $resume_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($jobs_application_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where($jobs_application_model->get_table_name().'.jobs1_id', '=', $request->jobs_id);

    if(!empty($request->user_id))
      $arr = $arr->where($jobs_application_model->get_table_name().'.user_id', '=', $request->user_id);

    if(!empty($request->status))
      $arr = $arr->where($jobs_application_model->get_table_name().'.status', '=', $request->status);

    if(isset($request->is_approve_corp))
      $arr = $arr->where($jobs_application_model->get_table_name().'.is_approve_corp', '=', $request->is_approve_corp);

    if(isset($request->is_approve_worker))
      $arr = $arr->where($jobs_application_model->get_table_name().'.is_approve_worker', '=', $request->is_approve_worker);

    if(!empty($request->arr_status)){
      $arr_status = json_decode($request->arr_status, true);
      $arr = $arr->where(function($where) use($jobs_application_model, $arr_status){
        foreach($arr_status as $status)
          $where = $where->orWhere($jobs_application_model->get_table_name().'.status', '=', $status);
      });
    }

    if(!empty($request->arr_is_approve_additional_salary)){
      $arr_is_approve_additional_salary = json_decode($request->arr_is_approve_additional_salary, true);
      $arr = $arr->where(function($where) use($jobs_application_model, $arr_is_approve_additional_salary){
        foreach($arr_is_approve_additional_salary as $status)
          $where = $where->orWhere($jobs_application_model->get_table_name().'.is_approve_additional_salary', '=', $status);
      });
    }

    if(empty($request->id) && empty($request->user_id) && Auth::check() && (Auth::user()->type->name == "customer_regular" || Auth::user()->type->name == "customer_oncall"))
      $arr = $arr->where($jobs_application_model->get_table_name().'.user_id', '=', Auth::user()->id);

    if(empty($request->order))
      $arr = $arr->orderBy('updated_at', 'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = JobsApplication::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs_application.action', [
      'jobs_application' => $data,
    ]);
  }

  public function detail(Request $request){
    $data = JobsApplication::find($request->id);
    $arr_user = User::all();
    $jobs_applied = JobsApplied::where('jobs_application_id', '=', $data->id)->first();
    $jobs_interview = JobsInterview::where('jobs_application_id', '=', $data->id)->first();
    $resume = Resume::where('user_id', '=', $data->user->id)->first();
    $data->general_quiz_score = count($data->user->general_quiz_result) > 0 ? $data->user->general_quiz_result[0]->score : 0;
    $arr_jobs = Jobs::where('status', '=', 'done')->get();

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "jobs_application.component.general_info",
      ],
      // [
      //   "id" => "resume_data",
      //   "component" => "jobs_application.component.resume_data",
      // ],
      // [
      //   "id" => "interview_data",
      //   "component" => "jobs_application.component.interview_data",
      // ],
      // [
      //   "id" => "applied_data",
      //   "component" => "jobs_application.component.applied_data",
      // ],
      // [
      //   "id" => "list_salary",
      //   "component" => "jobs_application.component.list_salary",
      // ],
      [
        "id" => "list_check_log",
        "component" => "jobs_application.component.list_check_log",
      ],
    ];

    if(!empty($data->resume))
      array_push($arr_tab, [
        "id" => "resume_data",
        "component" => "jobs_application.component.resume_data",
      ]);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs_application.detail', [
      'jobs_application' => $data,
      'jobs_applied' => $jobs_applied,
      // 'jobs_salary' => $jobs_salary,
      'jobs_interview' => $jobs_interview,
      'resume' => $resume,
      'arr_user' => $arr_user,
      'arr_tab' => $arr_tab,
      'arr_jobs' => $arr_jobs,
    ]);
  }

  public function post(Request $request){
    $resume = Resume::where('user_id', '=', !empty($request->user_id) ? $request->user_id : Auth::user()->id)->orderBy('created_at', 'desc')->first();
    $general_quiz_result = GeneralQuizResult::where('user_id', '=', !empty($request->user_id) ? $request->user_id : Auth::user()->id)->orderBy('created_at', 'desc')->first();
    $jobs = Jobs::find($request->jobs_id);

    $data = JobsApplication::where('jobs1_id', '=', $jobs->id)
      ->where('user_id', '=', !empty($request->user_id) ? $request->user_id : Auth::user()->id)
      ->first();
    if(!empty($data))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User already applied to this job',
      ]);

    $data = new JobsApplication();
    $data->user_id = !empty($request->user_id) ? $request->user_id : Auth::user()->id;
    $data->resume_id = !empty($resume) ? $resume->id : null;
    $data->general_quiz_result_id = !empty($general_quiz_result) ? $general_quiz_result->id : null;
    $data->jobs1_id = $jobs->id;
    $data->content = $request->content;
    $data->first_question = $request->first_question;
    if(!empty($request->status))
      $data->status = $request->status;
    $data->is_approve_corp = $data->user->type->name == "RO" || $data->user->type->name == "staff" ? 1 : 0;
    $data->is_approve_worker = $data->user->type->name == "customer_oncall" || $data->user->type->name == "customer_regular" ? 1 : 0;
    $data->salary_approve = $data->user->type->name == "customer_regular" ? $jobs->salary_regular : $jobs->salary_casual;
    $data->salary_init = $data->salary_approve;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function put(Request $request){
    $data = JobsApplication::find($request->id);
    $data->content = $request->content;
    $data->first_question = $request->first_question;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application');
  }

  public function upload_pkwt(Request $request){
    $file = [
      "file" => $request->file,
      "file_name" => $request->file->getClientOriginalName(),
    ];
    $data = JobsApplication::find($request->id);
    $this->file_helper->manage_file($file, $data, 'pkwt', 'pkwt_file_name', 'pkwt_mime_type');
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function upload_pkhl(Request $request){
    $file = [
      "file" => $request->file,
      "file_name" => $request->file->getClientOriginalName(),
    ];
    $data = JobsApplication::find($request->id);
    $this->file_helper->manage_file($file, $data, 'pkhl', 'pkhl_file_name', 'pkhl_mime_type');
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function change_status(Request $request){
    // $jobs_application_salary_helper = new JobsApplicationSalaryHelper();

    $data = JobsApplication::find($request->id);
    $data->status = $request->status;
    $data->save();

    $this->communication_helper->send_push_notif($data->user, 'Status Pekerjaan', $data->status == 'accepted' ? 'Selamat, Lamaran Pekerjaan '.$data->jobs->name.' Anda, telah diterima.' : 'Lamaran Pekerjaan '.$data->jobs->name.' Anda , telah ditolak', ["id" => $data->jobs->id, 'type' => "application"]);

    if($data->status == 'done'){
      $jobs = $data->jobs;
      $jobs->status = 'done';
      $jobs->end_at = Carbon::now();
      $jobs->save();

      // $jobs_application_salary_helper->sent_all_salary($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/customer/'.($data->user->type == "customer_oncall" ? 'oncall' : 'regular').'/detail?id='.$data->user->id.'&jobs_application_id='.$data->id);
  }

  public function change_approve_worker(Request $request){
    $data = JobsApplication::find($request->id);
    if(empty($data))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Jobs Application not found',
      ]);

    $data->is_approve_worker = $request->is_approve_worker;
    $data->status = $data->is_approve_worker == 1 ? 'accepted' : 'declined';
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application/detail?id='.$data->id);
  }

  public function delete(Request $request){
    $data = JobsApplication::find($request->id);
    $data->status = 'declined';
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');
    
    foreach($arr as $data)
      $this->relationship_helper->jobs_application($data, $request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
