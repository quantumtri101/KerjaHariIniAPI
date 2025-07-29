<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\JobsQualification;

class JobsQualificationController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_qualification.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->status_publish = __('general.'.($data->is_publish == 1 ? 'publish' : 'not_publish'));
      $data->status_publish_format = $data->is_publish == 1 ? 'publish' : 'not_publish';
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs.qualification.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_qualification_model = new JobsQualification();

    $arr = JobsQualification::select($jobs_qualification_model->get_table_name().'.*');

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
      $data = JobsQualification::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.qualification.action', [
      'jobs_qualification' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new JobsQualification();
    $data->jobs1_id = $request->jobs_id;
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs1_id);
  }

  public function put(Request $request){
    $data = JobsQualification::find($request->id);
    $data->jobs1_id = $request->jobs_id;
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs1_id);
  }

  public function delete(Request $request){
    JobsQualification::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs1_id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
