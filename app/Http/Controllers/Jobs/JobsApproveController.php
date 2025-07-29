<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsApproveHelper;

use App\Models\JobsApprove;
use App\Models\JobsApplication;
use App\Models\Jobs;
use App\Models\User;

class JobsApproveController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_approve.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->jobs_approve($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs.approve.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_approve_model = new JobsApprove();
    $user_model = new User();

    $arr = JobsApprove::select($jobs_approve_model->get_table_name().'.*', $jobs_approve_model->get_table_name().'.approve_at as document_str_format', $jobs_approve_model->get_table_name().'.updated_at as declined_at', $user_model->get_table_name().'.name as user_name',)
      ->join($user_model->get_table_name(), $jobs_approve_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where('jobs1_id', '=', $request->jobs_id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(empty($request->sort))
      $arr = $arr->orderBy('sort_order', 'asc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = JobsApprove::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.approve.action', [
      'jobs_approve' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new JobsApprove();
    $data->jobs1_id = $request->jobs_id;
    $data->user_id = $request->user_id;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function change_approve(Request $request){
    $helper = new JobsApproveHelper();

    $data = JobsApprove::where('jobs1_id', '=', $request->jobs_id)
      ->where('user_id', '=', Auth::user()->id)
      ->first();
    $data->status_approve = $request->status_approve;
    if($data->status_approve == 'approved')
      $data->approve_at = Carbon::now();
    else if($data->status_approve == 'declined')
      $data->decline_reason = $request->decline_reason;
    $data->save();

    if($data->status_approve == 'declined'){
      $before_jobs_approve = JobsApprove::where('jobs1_id', '=', $data->jobs->id)
        ->where('sort_order', '=', $data->sort_order - 1)
        ->first();

      if(!empty($before_jobs_approve)){
        $before_jobs_approve->status_approve = 'not_yet_approved';
        $before_jobs_approve->approve_at = null;
        $before_jobs_approve->save();

        // $jobs_document = JobsDocument::where('jobs1_id', '=', $data->jobs->id)->
      }
    }
    else if($data->status_approve == 'approved'){
      $after_jobs_approve = JobsApprove::where('jobs1_id', '=', $data->jobs->id)
        ->where('sort_order', '=', $data->sort_order + 1)
        ->first();
        

      if(!empty($after_jobs_approve)){
        $after_jobs_approve->status_approve = 'not_yet_approved';
        $after_jobs_approve->approve_at = null;
        $after_jobs_approve->decline_reason = null;
        $after_jobs_approve->save();

        // $jobs_document = JobsDocument::where('jobs1_id', '=', $data->jobs->id)->
      }
    }
    // else if($data->status_approve == 'approved')
      $helper->check_approve($data->jobs);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function delete(Request $request){
    $data = JobsApprove::find($request->id);
    $jobs = $data->jobs;
    $data->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$jobs->id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
