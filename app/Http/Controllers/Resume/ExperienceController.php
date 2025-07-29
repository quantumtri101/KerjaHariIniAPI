<?php
namespace App\Http\Controllers\Resume;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\Experience;
use App\Models\City;
use App\Models\Company;
use App\Models\Jobs;
use App\Models\Resume;

class ExperienceController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "experience.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data)
      $this->relationship_helper->experience($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || (Auth::check() && Auth::user()->type->name == 'customer_oncall') ? $arr[0] : $arr,
    ], 'view', 'experience.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $experience_modal = new Experience();
    $company_modal = new Company();
    $city_modal = new City();
    $resume_modal = new Resume();

    $arr = Experience::select($experience_modal->get_table_name().'.*')
      ->join($resume_modal->get_table_name(), $experience_modal->get_table_name().'.resume_id', '=', $resume_modal->get_table_name().'.id')
      ->join($company_modal->get_table_name(), $experience_modal->get_table_name().'.company_id', '=', $company_modal->get_table_name().'.id')
      ->join($city_modal->get_table_name(), $experience_modal->get_table_name().'.city_id', '=', $city_modal->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($experience_modal->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($experience_modal->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->resume_id))
      $arr = $arr->where($experience_modal->get_table_name().'.resume_id', '=', $request->resume_id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function detail(Request $request){
    $data = Experience::where('resume_id', '=', $request->resume_id)->first();

    return $this->get_data_helper->return_data($request, [], 'view', 'experience.detail', [
      'experience' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new Experience();
    $data->resume_id = $request->resume_id;
    $data->name = $request->name;
    $data->company_id = $request->company_id;
    $data->city_id = $request->city_id;
    $data->start_year = $request->start_year;
    $data->end_year = $request->end_year;
    $data->corporation = $request->corporation;
    $data->description = $request->description;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/experience');
  }

  public function put(Request $request){
    $data = Experience::find($request->id);
    $data->resume_id = $request->resume_id;
    $data->name = $request->name;
    $data->company_id = $request->company_id;
    $data->city_id = $request->city_id;
    $data->start_year = $request->start_year;
    $data->end_year = $request->end_year;
    $data->corporation = $request->corporation;
    $data->description = $request->description;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/experience');
  }

  public function delete(Request $request){
    Experience::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/experience');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
