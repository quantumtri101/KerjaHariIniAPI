<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\JobsApproveSalary;
use App\Models\User;

class JobsApproveSalaryController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_approve_salary.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    // foreach($arr as $key => $data)
    //   $this->relationship_helper->jobs_approve_salary($data, $key);

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

    $jobs_approve_salary_model = new JobsApproveSalary();
    $user_model = new User();

    $arr = JobsApproveSalary::select($jobs_approve_salary_model->get_table_name().'.*', $user_model->get_table_name().'.name as user_name',)
      ->join($user_model->get_table_name(), $jobs_approve_salary_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where($jobs_approve_salary_model->get_table_name().'.jobs1_id', '=', $request->jobs_id);

    if(!empty($request->status_approve))
      $arr = $arr->where($jobs_approve_salary_model->get_table_name().'.status_approve', '=', $request->status_approve);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
