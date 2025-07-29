<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Models\Type;
use App\Models\Branch;
use App\Models\Outlet;
use App\Models\MemberUser;
use App\Models\JobsRecommendation;
use App\Models\JobsRecommendationCity;
use App\Models\JobsRecommendationSubCategory;
use App\Models\JobsRangeSalary;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Resume;
use App\Models\Experience;
use App\Models\JobsApplication;
use App\Models\Jobs;
use App\Models\Event;

class UserController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "user.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->user($data, $request);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'user.student.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $type_model = new Type();
    $user_model = new User();
    $resume_model = new Resume();
    $experience_model = new Experience();
    $jobs_recommendation_model = new JobsRecommendation();
    $jobs_recommendation_city_model = new JobsRecommendationCity();
    $jobs_recommendation_sub_category_model = new JobsRecommendationSubCategory();
    $sub_category_model = new SubCategory();
    $category_model = new Category();
    $jobs_model = new Jobs();
    $event_model = new Event();
    $jobs_range_salary_model = new JobsRangeSalary();
    $jobs_application_model = new JobsApplication();

    $temp_resume = Resume::select('user_id')
      ->selectRaw('MAX(id) as id')
      ->groupBy('user_id');

    $temp_resume1 = Resume::select($resume_model->get_table_name().'.*')
      ->joinSub($temp_resume, 'temp', 'temp.id', '=', $resume_model->get_table_name().'.id');

    $temp_jobs_recommendation = JobsRecommendation::select($jobs_recommendation_model->get_table_name().'.user_id')
      ->selectRaw('MAX('.$jobs_recommendation_model->get_table_name().'.id) as id')
      ->groupBy('user_id')
      ->join($jobs_range_salary_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.jobs_range_salary_id', '=', $jobs_range_salary_model->get_table_name().'.id')
      ->join($jobs_recommendation_city_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.id', '=', $jobs_recommendation_city_model->get_table_name().'.jobs_recommendation_id')
      ->join($jobs_recommendation_sub_category_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.id', '=', $jobs_recommendation_sub_category_model->get_table_name().'.jobs_recommendation_id')
      ->join($user_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->joinSub($temp_resume1, $resume_model->get_table_name(), $resume_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($experience_model->get_table_name(), $experience_model->get_table_name().'.resume_id', '=', $resume_model->get_table_name().'.id')
      ->join($sub_category_model->get_table_name(), $jobs_recommendation_sub_category_model->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
      ->join($category_model->get_table_name(), $sub_category_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id');

    if(!empty($request->jobs_id) && !empty($request->api_type) && $request->api_type == "recommendation"){
      $jobs = Jobs::find($request->jobs_id);
      $min_year = Carbon::now()->subYears($jobs->criteria[0]->min_age);
      $max_year = Carbon::now()->subYears($jobs->criteria[0]->max_age);
      $temp_jobs_recommendation = $temp_jobs_recommendation->where($jobs_range_salary_model->get_table_name().'.min_salary', '<=', $jobs->salary_casual)
        ->where($jobs_range_salary_model->get_table_name().'.max_salary', '>=', $jobs->salary_casual)
        ->where($sub_category_model->get_table_name().'.id', '=', $jobs->sub_category->id)
        ->where($user_model->get_table_name().'.gender', '=', $jobs->criteria[0]->gender == "female" ? 0 : 1)
        ->whereBetween($resume_model->get_table_name().'.birth_date', [$min_year, $max_year]);

      if($jobs->criteria[0]->is_same_place == 1)
        $temp_jobs_recommendation = $temp_jobs_recommendation->where($resume_model->get_table_name().'.city_id', '=', $jobs->city->id);

      if($jobs->criteria[0]->is_working_same_company == 1){
        $temp_jobs_recommendation = $temp_jobs_recommendation->where($experience_model->get_table_name().'.company_id', '=', $jobs->company->id);

        $temp_jobs_application = JobsApplication::where('company_id', '=', $jobs->company->id)->where('status', '=', 'done');
      }
      
    }

    $jobs_application_temp = JobsApplication::select('user_id')
      ->selectRaw('MAX(id) as id')
      ->groupBy('user_id');

    $jobs_application_temp1 = JobsApplication::select($jobs_application_model->get_table_name().'.*')
      ->joinSub($jobs_application_temp, 'temp', $jobs_application_model->get_table_name().'.id', '=', 'temp.id');

    $arr = User::select($user_model->get_table_name().'.*', $user_model->get_table_name().'.created_at as created_at_format', $user_model->get_table_name().'.updated_at as updated_at_format', $jobs_model->get_table_name().'.name as jobs_name')
      ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->leftJoinSub($jobs_application_temp1, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->leftJoin($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->leftJoin($event_model->get_table_name(), $jobs_model->get_table_name().'.event_id', '=', $event_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($user_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($user_model->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->arr_api_type)){
      $arr_api_type = json_decode($request->arr_api_type, true);
      foreach($arr_api_type as $api_type){
        if(!empty($request->jobs_id) && $api_type == "recommendation"){
          $jobs = Jobs::find($request->jobs_id);

          $arr = $arr->joinSub($temp_jobs_recommendation, $jobs_recommendation_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
            ->where($user_model->get_table_name().'.is_active', '=', 1);

          if($jobs->criteria[0]->is_working_same_company == 1)
            $arr = $arr->joinSub($temp_jobs_application, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');
        }
        else if(!empty($request->jobs_id) && $api_type == "not_recommendation"){
          $jobs = Jobs::find($request->jobs_id);

          $arr = $arr->leftJoinSub($temp_jobs_recommendation, $jobs_recommendation_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
            ->whereNull($jobs_recommendation_model->get_table_name().'.id')
            ->where($user_model->get_table_name().'.is_active', '=', 1);
        }
        else if(!empty($request->jobs_id) && $api_type == "not_applicant"){
          $jobs = Jobs::find($request->jobs_id);

          $arr = $arr->where(function($where) use($jobs, $user_model){
            foreach($jobs->application as $application)
              $where = $where->orWhere($user_model->get_table_name().'.id', '!=', $application->user->id);
          });
        }
      }
    }

    if(!empty($request->event_id)){
      $arr = $arr->where($event_model->get_table_name().'.id', '=', $request->event_id);
      // $event = Event::find($request->event_id);

      // $arr = $arr->where(function($where) use($event, $user_model) {
      //   foreach($event->jobs as $jobs){
      //     foreach($jobs->application as $jobs_application)
      //       $where = $where->orWhere($user_model->get_table_name().'.id', '=', $jobs_application->user->id);
      //   }
      // });
    }

    if(!empty($request->type_id))
      $arr = $arr->where($user_model->get_table_name().'.type_id', '=', $request->type_id);

    if(!empty($request->is_active))
      $arr = $arr->where($user_model->get_table_name().'.is_active', '=', $request->is_active);

    if(!empty($request->type))
      $arr = $arr->where($type_model->get_table_name().'.name', 'like', $request->type);

    if(isset($request->is_working))
      $arr = $arr->where($user_model->get_table_name().'.is_working', '=', $request->is_working);

    if(!empty($request->company_id))
      $arr = $arr->where($user_model->get_table_name().'.company_id', '=', $request->company_id);

    if(!empty($request->arr_exclude)){
      $arr_exclude = json_decode($request->arr_exclude, true);
      foreach($arr_exclude as $exclude)
        $arr = $arr->where($user_model->get_table_name().'.id', '!=', $exclude);
    }

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('name', 'asc');
    // dd($arr->getBindings());

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $type = Type::where('name', 'like', 'operator')->first();
    $user = User::where('email','=',$request->email)->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $password = $this->string_helper->generateRandomString($this->str_length);
    $data = new User();
    $data->type_id = $type->id;
    $data->name = $request->name;
    $data->phone = $request->phone;
    $data->email = $request->email;
    $data->password = Hash::make($password);
    $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator');
  }

  public function put(Request $request){
    // dd($request->all());
    $user = User::where('email','=',$request->email)
      ->where('id', '!=', $request->id)
      ->first();
    if(!empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ], 'back', '');

    $data = User::find($request->id);
    $data->name = $request->name;
    $data->phone = $request->phone;
    $data->email = $request->email;
    $this->file_helper->manage_image($request->image, $data, 'user', 'file_name');
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator');
  }
  
  public function request_delete(Request $request){
    $user = User::where('email', 'like', $request->email)->first();
    if(empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_not_found'),
      ], 'back', '', [
        'error' => __('controller.user_not_found'),
      ]);
  
    $user->request_delete_at = Carbon::now();
    $user->save();
  
    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/request-delete/finish');
  }

  public function delete(Request $request){
    User::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/user/operator');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
