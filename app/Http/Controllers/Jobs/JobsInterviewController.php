<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\JobsInterview;
use App\Models\JobsApplication;

class JobsInterviewController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_interview.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

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

    $jobs_interview_model = new JobsInterview();

    $arr = JobsInterview::select($jobs_interview_model->get_table_name().'.*');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where('jobs1_id', '=', $request->jobs_id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = JobsInterview::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.interview.action', [
      'jobs_interview' => $data,
    ]);
  }

  public function post(Request $request){
    $jobs_application = JobsApplication::find($request->jobs_application_id);

    $data = new JobsInterview();
    $data->jobs_application_id = $jobs_application->id;
    $data->interviewer_name = $jobs_application->jobs->interviewer_name;
    $data->interviewer_phone = $jobs_application->jobs->interviewer_phone;
    $data->schedule = Carbon::createFromFormat('d-m-Y H:i', $request->schedule);
    $data->type = $request->type;
    if($data->type == 'online')
      $data->zoom_url = $request->zoom_url;
    else if($data->type == 'offline')
      $data->location = $request->location;
    $data->save();

    $jobs_application->status = 'interview';
    $jobs_application->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application/detail?id='.$jobs_application->id);
  }

  public function put(Request $request){
    $data = JobsInterview::find($request->id);
    $data->interviewer_name = $jobs_application->jobs->interviewer_name;
    $data->interviewer_phone = $jobs_application->jobs->interviewer_phone;
    $data->schedule = Carbon::createFromFormat('d-m-Y H:i', $request->schedule);
    $data->type = $request->type;
    if($data->type == 'online')
      $data->zoom_url = $request->zoom_url;
    else if($data->type == 'offline')
      $data->location = $request->location;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/application/detail?id='.$data->jobs_application->id);
  }

  public function delete(Request $request){
    $data = JobsInterview::find($request->id);
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
