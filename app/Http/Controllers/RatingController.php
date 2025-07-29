<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\Rating;
use App\Models\Jobs;
use App\Models\JobsApplication;
use App\Models\User;

class RatingController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "rating.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'rating.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $rating_model = new Rating();
    $jobs_model = new Jobs();
    $user_model = new User();

    $arr = Rating::select($rating_model->get_table_name().'.*', 'staff.name as staff_name', 'user.name as user_name', $jobs_model->get_table_name().'.name as jobs_name')
      ->join($user_model->get_table_name().' as staff', $rating_model->get_table_name().'.staff_id', '=', 'staff.id')
      ->join($user_model->get_table_name().' as user', $rating_model->get_table_name().'.user_id', '=', 'user.id')
      ->join($jobs_model->get_table_name(), $rating_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($rating_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->jobs_application_id))
      $arr = $arr->where('jobs_application_id', '=', $request->jobs_application_id);

    if(!empty($request->user_id))
      $arr = $arr->where('user_id', '=', $request->user_id);

    if(!empty($request->staff_id))
      $arr = $arr->where('staff_id', '=', $request->staff_id);

    if(!empty($request->jobs1_id))
      $arr = $arr->where('jobs1_id', '=', $request->jobs1_id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $jobs = Jobs::find($request->jobs_id);
    $staff = User::find($request->staff_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs->id)->where('user_id', '=', $staff->id)->first();

    $data = new Rating();
    $data->jobs_application_id = $jobs_application->id;
    $data->jobs1_id = $jobs->id;
    $data->staff_id = $staff->id;
    $data->user_id = Auth::user()->id;
    $data->rating = str_replace(',', '.', $request->rating);
    $data->review = $request->review;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
