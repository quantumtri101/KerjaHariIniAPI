<?php
namespace App\Http\Controllers\Resume;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\ResumeHelper;

use App\Models\Resume;
use App\Models\City;
use App\Models\Bank;
use App\Models\Jobs;
use App\Models\User;

class ResumeController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "resume.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data)
      $this->relationship_helper->resume($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || (Auth::check() && Auth::user()->type->name == 'customer_oncall') ? $arr[0] : $arr,
    ], 'view', 'resume.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $resume_modal = new Resume();
    $bank_modal = new Bank();
    $city_modal = new City();
    $user_modal = new User();

    $arr = Resume::select($resume_modal->get_table_name().'.*')
      ->join($user_modal->get_table_name(), $resume_modal->get_table_name().'.user_id', '=', $user_modal->get_table_name().'.id')
      ->join($bank_modal->get_table_name(), $resume_modal->get_table_name().'.bank_id', '=', $bank_modal->get_table_name().'.id')
      ->leftJoin($city_modal->get_table_name(), $resume_modal->get_table_name().'.city_id', '=', $city_modal->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($resume_modal->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($resume_modal->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->user_id))
      $arr = $arr->where($resume_modal->get_table_name().'.user_id', '=', $request->user_id);

    if(empty($request->id) && empty($request->user_id) && Auth::check() && Auth::user()->type->name == 'customer_oncall')
      $arr = $arr->where($resume_modal->get_table_name().'.user_id', '=', Auth::user()->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function detail(Request $request){
    $data = Resume::where('user_id', '=', $request->user_id)->first();
    $arr_jobs = Jobs::where('status', '=', 'done')->get();

    return $this->get_data_helper->return_data($request, [], 'view', 'resume.detail', [
      'resume' => $data,
      'arr_jobs' => $arr_jobs,
    ]);
  }

  public function post(Request $request){
    $helper = new ResumeHelper();
    if(!empty($request->marital_status_id))
			$marital_status = $request->marital_status_id;
		else if(empty($marital_status) && !empty($request->marital_status))
			$marital_status = $helper->get_marital_status($request);

    $data = Resume::where('user_id', '=', Auth::user()->id)->first();

    if(empty($data))
      $data = new Resume();
    $data->city_id = $request->city_id;
    $data->bank_id = $request->bank_id;
    $data->education_id = $request->education_id;
    $data->user_id = Auth::user()->id;
    $data->name = $request->name;
    $data->phone = $request->phone;
    $data->birth_date = $request->birth_date;
    $data->address = $request->address;
    $data->marital_status = $marital_status;
    $data->height = $request->height;
    $data->weight = $request->weight;
    $data->acc_no = $request->acc_no;
		$data->acc_name = $request->acc_name;
    $data->save();

    if((!empty($request->id_image) && $request->id_image != "") || (!empty($request->selfie_image) && $request->selfie_image != "")){
      $data->refresh();
      if(!empty($request->id_image) && $request->id_image != "")
        $this->file_helper->manage_image($request->id_image, $data, 'resume_id', 'id_file_name');
      if(!empty($request->selfie_image) && $request->selfie_image != "")
        $this->file_helper->manage_image($request->selfie_image, $data, 'resume_selfie', 'selfie_file_name');
      $data->save();
    }

    $helper->add_experience($request->arr_experience, $data);
    $helper->add_skill($request->arr_skill, $data);

    $user = Auth::user();
    $user->id_no = $request->id_no;
    $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/resume');
  }

  public function put(Request $request){
    $helper = new ResumeHelper();

    if(empty($request->id))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Resume not found',
      ]);

    if(!empty($request->marital_status_id))
			$marital_status = $request->marital_status_id;
		else if(!empty($request->marital_status))
      $marital_status = $helper->get_marital_status($request);

    $data = Resume::find($request->id);
    if(!empty($request->city_id))
      $data->city_id = $request->city_id;
    if(!empty($request->bank_id))
      $data->bank_id = $request->bank_id;
    if(!empty($request->education_id))
      $data->education_id = $request->education_id;
    $data->user_id = Auth::user()->id;
    if(!empty($request->name))
      $data->name = $request->name;
    if(!empty($request->phone))
      $data->phone = $request->phone;
    if(!empty($request->birth_date))
      $data->birth_date = $request->birth_date;
    if(!empty($request->address))
      $data->address = $request->address;
    if(!empty($marital_status))
      $data->marital_status = $marital_status;
    if(!empty($request->height))
      $data->height = $request->height;
    if(!empty($request->weight))
      $data->weight = $request->weight;
    if(!empty($request->acc_no))
      $data->acc_no = $request->acc_no;
		if(!empty($request->acc_name))
			$data->acc_name = $request->acc_name;
    $data->save();

    if((!empty($request->id_image) && $request->id_image != "") || (!empty($request->selfie_image) && $request->selfie_image != "")){
      $data->refresh();
      if(!empty($request->id_image) && $request->id_image != "")
        $this->file_helper->manage_image($request->id_image, $data, 'resume_id', 'id_file_name');
      if(!empty($request->selfie_image) && $request->selfie_image != "")
        $this->file_helper->manage_image($request->selfie_image, $data, 'resume_selfie', 'selfie_file_name');
      $data->save();
    }

    if(!empty($request->arr_experience))
      $helper->add_experience($request->arr_experience, $data);
    if(!empty($request->arr_skill))
      $helper->add_skill($request->arr_skill, $data);

    if(!empty($request->id_no)){
      $user = Auth::user();
      $user->id_no = $request->id_no;
      $user->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/resume');
  }

  public function delete(Request $request){
    Resume::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/resume');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
