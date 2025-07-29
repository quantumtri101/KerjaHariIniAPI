<?php
namespace App\Http\Controllers\Resume;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\ResumeSkill;
use App\Models\City;
use App\Models\Skill;
use App\Models\Jobs;
use App\Models\Resume;

class ResumeSkillController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "resume_skill.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data)
      $this->relationship_helper->resume_skill($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || (Auth::check() && Auth::user()->type->name == 'customer_oncall') ? $arr[0] : $arr,
    ], 'view', 'resume_skill.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $resume_skill_modal = new ResumeSkill();
    $skill_modal = new Skill();
    $city_modal = new City();
    $resume_modal = new Resume();

    $arr = ResumeSkill::select($resume_skill_modal->get_table_name().'.*')
      ->join($resume_modal->get_table_name(), $resume_skill_modal->get_table_name().'.resume_id', '=', $resume_modal->get_table_name().'.id')
      ->join($skill_modal->get_table_name(), $resume_skill_modal->get_table_name().'.skill_id', '=', $skill_modal->get_table_name().'.id')
      ->join($city_modal->get_table_name(), $resume_skill_modal->get_table_name().'.city_id', '=', $city_modal->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($resume_skill_modal->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($resume_skill_modal->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->resume_id))
      $arr = $arr->where($resume_skill_modal->get_table_name().'.resume_id', '=', $request->resume_id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function detail(Request $request){
    $data = ResumeSkill::where('resume_id', '=', $request->resume_id)->first();

    return $this->get_data_helper->return_data($request, [], 'view', 'resume_skill.detail', [
      'resume_skill' => $data,
    ]);
  }

  public function post(Request $request){
    if(!empty($request->skill_id))
      $skill = Skill::find($request->skill_id);
    if(!empty($request->custom_skill)){
      $skill = Skill::where('name', 'like', $request->custom_skill)->first();
      if(!empty($skill)){
        $skill = new Skill();
        $skill->name = $request->custom_skill;
        $skill->save();
      }
    }

    $data = new ResumeSkill();
    $data->resume_id = $request->resume_id;
    $data->skill_id = $skill->id;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/resume/skill');
  }

  public function put(Request $request){
    if(!empty($request->skill_id))
      $skill = Skill::find($request->skill_id);
    if(!empty($request->custom_skill)){
      $skill = Skill::where('name', 'like', $request->custom_skill)->first();
      if(!empty($skill)){
        $skill = new Skill();
        $skill->name = $request->custom_skill;
        $skill->save();
      }
    }

    $data = ResumeSkill::find($request->id);
    $data->resume_id = $request->resume_id;
    $data->skill_id = $skill->id;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/resume/skill');
  }

  public function delete(Request $request){
    ResumeSkill::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/resume/skill');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
