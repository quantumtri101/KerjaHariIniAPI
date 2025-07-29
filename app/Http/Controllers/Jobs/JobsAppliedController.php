<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsApplicationSalaryHelper;

use App\Models\JobsApplied;
use App\Models\JobsApplication;
use App\Models\Jobs;
use App\Models\Resume;
use App\Models\GeneralQuizResult;

class JobsAppliedController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_applied.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs.applied.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_applied_model = new JobsApplied();

    $arr = JobsApplied::select($jobs_applied_model->get_table_name().'.*');

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
      $data = JobsApplied::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.applied.action', [
      'jobs_applied' => $data,
    ]);
  }

  public function post(Request $request){
    $jobs_application_salary_helper = new JobsApplicationSalaryHelper();
    $jobs_application = JobsApplication::find($request->jobs_application_id);
    $jobs = Jobs::find($request->jobs_id);

    if(empty($jobs_application)){
      $resume = Resume::where('user_id', '=', $request->user_id)->orderBy('created_at', 'desc')->first();
      $general_quiz_result = GeneralQuizResult::where('user_id', '=', $request->user_id)->orderBy('created_at', 'desc')->first();

      $jobs_application = new JobsApplication();
      $jobs_application->user_id = $request->user_id;
      if(!empty($resume))
        $jobs_application->resume_id = $resume->id;
      if(!empty($general_quiz_result))
        $jobs_application->general_quiz_result_id = $general_quiz_result->id;
      $jobs_application->jobs1_id = $request->jobs_id;
      $jobs_application->save();
    }

    $data = new JobsApplied();
    $data->jobs_application_id = $jobs_application->id;
    $data->pic_name = $jobs->pic_name;
    $data->pic_phone = $jobs->pic_phone;
    $data->brief_schedule = Carbon::createFromFormat('d-m-Y H:i', $request->brief_schedule);
    $data->brief_location = $request->brief_location;
    $data->work_schedule = Carbon::createFromFormat('d-m-Y H:i', $request->work_schedule);
    $data->work_location = $request->work_location;
    // $data->latitude = $request->latitude;
    // $data->longitude = $request->longitude;
    $data->save();

    if($jobs_application->generated_from == 'user'){
      $jobs_application->status = 'accepted';
      $jobs_application->is_approve_worker = 1;
    }
    $jobs_application->is_approve_corp = 1;
    $jobs_application->save();

    $jobs = $jobs_application->jobs;
    $jobs->status = 'accepted';
    $jobs->worker_id = $jobs_application->user->id;
    $jobs->start_at = Carbon::now();
    $jobs->save();

    $jobs_application_salary_helper->add_salary($jobs);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', !empty($request->from) && $request->from == 'system' ? '/jobs/detail?id='.$jobs->id : '/jobs/application/detail?id='.$jobs_application->id);
  }

  public function put(Request $request){
    $jobs_application_salary_helper = new JobsApplicationSalaryHelper();

    $data = JobsApplied::find($request->id);
    $data->pic_name = $jobs->pic_name;
    $data->pic_phone = $jobs->pic_phone;
    $data->brief_schedule = Carbon::createFromFormat('d-m-Y H:i', $request->brief_schedule);
    $data->brief_location = $request->brief_location;
    $data->work_schedule = Carbon::createFromFormat('d-m-Y H:i', $request->work_schedule);
    $data->work_location = $request->work_location;
    // $data->latitude = $request->latitude;
    // $data->longitude = $request->longitude;
    $data->save();

    $jobs = $data->jobs_application->jobs;
    $jobs_application_salary_helper->add_salary($jobs);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application/detail?id='.$jobs_application->id);
  }

  public function delete(Request $request){
    $data = JobsApplied::find($request->id);
    $jobs_application = $data->jobs_application;
    $data->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application/detail?id='.$jobs_application->id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
