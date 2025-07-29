<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsApproveHelper;

use App\Models\JobsDocument;
use App\Models\Jobs;
use App\Models\JobsApprove;

class JobsDocumentController extends BaseController{
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

    $jobs_interview_model = new JobsDocument();

    $arr = JobsDocument::select($jobs_interview_model->get_table_name().'.*');

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
      $data = JobsDocument::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.interview.action', [
      'jobs_interview' => $data,
    ]);
  }

  public function post(Request $request){
    $helper = new JobsApproveHelper();

    $arr_image = json_decode($request->arr_image, true);
    $jobs = Jobs::find($request->jobs_id);
    $jobs_approve = JobsApprove::where('jobs1_id', '=', $jobs->id)->where('user_id', '=', Auth::user()->id)->first();
    // dd($arr_image);
    foreach($arr_image as $image){
      $data = new JobsDocument();
      $data->jobs1_id = $request->jobs_id;
      $data->jobs_approve_id = $jobs_approve->id;
      $data->save();

      $this->file_helper->manage_file($image, $data, 'jobs_document', 'file_name', 'mime_type');
      $data->save();
    }

    $jobs_approve->status_approve = "approved";
    $jobs_approve->approve_at = Carbon::now();
    $jobs_approve->save();

    $after_jobs_approve = JobsApprove::where('jobs1_id', '=', $jobs_approve->jobs->id)
      ->where('sort_order', '=', $jobs_approve->sort_order + 1)
      ->first();
        
    if(!empty($after_jobs_approve)){
      $after_jobs_approve->status_approve = 'not_yet_approved';
      $after_jobs_approve->approve_at = null;
      $after_jobs_approve->decline_reason = null;
      $after_jobs_approve->save();

      // $jobs_document = JobsDocument::where('jobs1_id', '=', $data->jobs->id)->
    }
    $helper->check_approve($jobs);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$jobs->id);
  }

  public function put(Request $request){
    $jobs = Jobs::find($request->jobs_id);
    $jobs_approve = JobsApprove::where('jobs1_id', '=', $jobs->id)->where('user_id', '=', Auth::user()->id)->first();

    $data = new JobsDocument();
    $data->jobs1_id = $request->jobs_id;
    $data->jobs_approve_id = $jobs_approve->id;
    $data->save();
    
    if(!empty($request->file) && $request->file != ""){
      $this->file_helper->manage_file($request->file, $data, 'jobs');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function delete(Request $request){
    $data = JobsDocument::find($request->id);
    $jobs = $data->jobs;
    $data->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->jobs->id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
