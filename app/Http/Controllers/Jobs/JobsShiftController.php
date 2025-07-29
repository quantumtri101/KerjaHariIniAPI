<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsShiftHelper;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;

use App\Models\JobsShift;
use App\Models\CheckLog;
use App\Models\SubCategory;
use App\Models\Jobs;
use App\Models\JobsApplication;
use App\Models\Event;

class JobsShiftController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_shift.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $key => $data)
      $this->relationship_helper->jobs_shift($data, $key);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs.interview.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_shift_model = new JobsShift();
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();
    $check_log_model = new CheckLog();
    $sub_category_model = new SubCategory();
    $event_model = new Event();

    $temp_application = JobsApplication::select('jobs1_id')
      ->selectRaw('MAX(id) as id')
      ->selectRaw('COUNT(id) as total_applicant')
      ->groupBy('jobs1_id');

    $temp_application1 = JobsApplication::select($jobs_application_model->get_table_name().'.*', 'temp.total_applicant')
      ->joinSub($temp_application, 'temp', 'temp.id', '=', $jobs_application_model->get_table_name().'.id');

    $temp_check_in = CheckLog::select('jobs1_id')
      ->selectRaw('MAX(id) as id')
      ->selectRaw('COUNT(id) as total_check_log')
      ->where('type', '=', 'check_in')
      ->groupBy('jobs1_id');

    $temp_check_in1 = CheckLog::select($check_log_model->get_table_name().'.*', 'temp.total_check_log')
      ->joinSub($temp_check_in, 'temp', 'temp.id', '=', $check_log_model->get_table_name().'.id');

    $temp_check_out = CheckLog::select('jobs1_id')
      ->selectRaw('MAX(id) as id')
      ->selectRaw('COUNT(id) as total_check_log')
      ->where('type', '=', 'check_out')
      ->groupBy('jobs1_id');

    $temp_check_out1 = CheckLog::select($check_log_model->get_table_name().'.*', 'temp.total_check_log')
      ->joinSub($temp_check_out, 'temp', 'temp.id', '=', $check_log_model->get_table_name().'.id');



    $arr = JobsShift::select($jobs_shift_model->get_table_name().'.*', $jobs_shift_model->get_table_name().'.start_date as name', $jobs_shift_model->get_table_name().'.start_date as start_date_format', $jobs_shift_model->get_table_name().'.start_date as working_date_format', $jobs_shift_model->get_table_name().'.end_date as end_date_format', $sub_category_model->get_table_name().'.name as sub_category_name', $jobs_model->get_table_name().'.name as jobs_name', $event_model->get_table_name().'.name as event_name',)
      ->selectRaw('check_in.total_check_log as total_check_in_format')
      ->selectRaw('check_out.total_check_log as total_check_out_format')
      ->selectRaw($jobs_application_model->get_table_name().'.total_applicant')
      ->join($jobs_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->leftJoinSub($temp_application1, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->join($sub_category_model->get_table_name(), $jobs_model->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
      ->leftJoinSub($temp_check_in1, 'check_in', 'check_in.jobs_shift_id', '=', $jobs_shift_model->get_table_name().'.id')
      ->leftJoinSub($temp_check_out1, 'check_out', 'check_out.jobs_shift_id', '=', $jobs_shift_model->get_table_name().'.id')
      ->join($event_model->get_table_name(), $jobs_model->get_table_name().'.event_id', '=', $event_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.jobs1_id', '=', $request->jobs_id);

    if(isset($request->is_approve))
      $arr = $arr->where($jobs_model->get_table_name().'.is_approve', '=', $request->is_approve);

    if(isset($request->is_approve_salary))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.is_approve_salary', '=', $request->is_approve_salary);

    if(isset($request->is_approve_check_log))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.is_approve_check_log', '=', $request->is_approve_check_log);

    if(isset($request->is_requested_salary))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.is_requested_salary', '=', $request->is_requested_salary);

    if(isset($request->is_requested_check_log))
      $arr = $arr->where($jobs_shift_model->get_table_name().'.is_requested_check_log', '=', $request->is_requested_check_log);

    if(!empty($request->arr_type)){
      $arr_type = json_decode($request->arr_type, true);
      foreach($arr_type as $type1){
        if($type1 == "applicant_exist")
          $arr = $arr->having('total_applicant', '>', 0);
        else if($type1 == "applicant_full")
          $arr = $arr->whereRaw($jobs_application_model->get_table_name().'.total_applicant = '.$jobs_model->get_table_name().'.num_people_required');
        else if($type1 == "done_check_log")
          $arr = $arr->where($jobs_shift_model->get_table_name().'.is_approve_check_log', '=', '1');
      }
    }

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(empty($request->id) && empty($request->company_id) && Auth::check() && !empty(Auth::user()->company))
      $arr = $arr->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = JobsShift::find($request->id);
    $arr_application = JobsApplication::where('jobs1_id', '=', $data->id)->where('status', '=', 'working')->get();

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs_shift.detail', [
      'data' => $data,
      'arr_application' => $arr_application,
    ]);
  }

  public function change_approve(Request $request){
    $helper = new JobsShiftHelper();
    $data = JobsShift::find($request->id);
    $data->approve_type = $request->approve_type;
    $data->save();

    if($data->approve_type == "all"){
      $arr_check_log = CheckLog::where('jobs_shift_id', '=', $data->id)->get();
      foreach($arr_check_log as $check_log){
        $check_log->is_approve_check_log = 'approved';
        $check_log->approved_at = Carbon::now();
        $check_log->save();
      }
    }
    $helper->check_approve_check_log($data);

    return $this->get_data_helper->return_data($request, [], 'redirect', '/check-log/detail?id='.$data->id);
  }

  public function change_approve_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();

    $data = JobsShift::find($request->id);
    $data->approve_salary_type = $request->approve_salary_type;
    $data->save();

    if($data->approve_salary_type == "all"){
      $arr_jobs_application = JobsApplication::where('jobs1_id', '=', $data->jobs->id)->get();
      foreach($arr_jobs_application as $jobs_application){
        $jobs_application->is_approve_salary = 'approved';
        $jobs_application->save();

        $salary_transaction_helper->add_transaction($jobs_application->user, $jobs_application->salary_approve, null, 'in', 'Gaji dari Pekerjaan ID #'.$jobs_application->jobs->id);

        $jobs_application->salary_sent_at = Carbon::now();
        $jobs_application->save();
      }
    }
    $helper->check_approve_salary($data);

    return $this->get_data_helper->return_data($request, [], 'redirect', '/salary/detail?id='.$data->id);
  }

  public function change_approve_additional_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();

    $data = JobsShift::find($request->id);
    $data->approve_additional_salary_type = $request->approve_additional_salary_type;
    $data->save();

    if($data->approve_additional_salary_type == "all"){
      $arr_jobs_application = JobsApplication::where('jobs1_id', '=', $data->jobs->id)->get();
      foreach($arr_jobs_application as $jobs_application){
        $jobs_application->is_approve_additional_salary = 'approved';
        $jobs_application->save();

        $salary_transaction_helper->add_transaction($jobs_application->user, $jobs_application->additional_salary, null, 'in', 'Tambahan Gaji dari Pekerjaan ID #'.$jobs_application->jobs->id);

        $jobs_application->additional_salary_sent_at = Carbon::now();
        $jobs_application->save();
      }
    }
    $helper->check_approve_salary($data);

    return $this->get_data_helper->return_data($request, [], 'redirect', '/salary/detail?id='.$data->id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
