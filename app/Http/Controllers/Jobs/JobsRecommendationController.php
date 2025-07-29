<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsRecommendationHelper;

use App\Models\JobsRecommendation;
use App\Models\JobsRangeSalary;
use App\Models\City;
use App\Models\Category;
use App\Models\User;

class JobsRecommendationController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs_recommendation.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->range_salary_format = !empty($data->range_salary) ? "Rp. ".number_format($data->range_salary->min_salary, 0, ',', '.')." - ".number_format($data->range_salary->max_salary, 0, ',', '.') : '-';
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'jobs_recommendation.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_recommendation_model = new JobsRecommendation();
    $user_model = new User();
    $jobs_range_salary_model = new JobsRangeSalary();
    $category_model = new Category();
    $city_model = new City();

    $arr = JobsRecommendation::select($jobs_recommendation_model->get_table_name().'.*', $user_model->get_table_name().'.name as user_name',)
      ->selectRaw('CONCAT('.$jobs_range_salary_model->get_table_name().'.min_salary, " - ", '.$jobs_range_salary_model->get_table_name().'.max_salary) as range_salary_format')
      ->join($user_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($jobs_range_salary_model->get_table_name(), $jobs_recommendation_model->get_table_name().'.jobs_range_salary_id', '=', $jobs_range_salary_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($jobs_recommendation_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->jobs_id))
      $arr = $arr->where($jobs_recommendation_model->get_table_name().'.jobs1_id', '=', $request->jobs_id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function detail(Request $request){
    $data = JobsRecommendation::find($request->id);

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "jobs_recommendation.component.general_info",
      ],
      [
        "id" => "list_jobs",
        "component" => "jobs_recommendation.component.list_jobs",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs_recommendation.detail', [
      'jobs_recommendation' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  public function post(Request $request){
    $helper = new JobsRecommendationHelper();

    // if(empty($request->jobs_range_salary_id))
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'Range Salary is Empty',
    //   ]);

    $data = JobsRecommendation::where('user_id', '=', Auth::user()->id)->first();

    if(empty($data))
      $data = new JobsRecommendation();
    // $data->city_id = $request->city_id;
    // $data->category_id = $request->category_id;
    
    $data->jobs_range_salary_id = $request->jobs_range_salary_id;
    $data->user_id = Auth::user()->id;
    $data->save();

    $helper->edit_sub_category($request->arr_sub_category, $data);
    $helper->edit_city($request->arr_city, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/recommendation');
  }

  public function put(Request $request){
    $helper = new JobsRecommendationHelper();

    // if(empty($request->jobs_range_salary_id))
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'Range Salary is Empty',
    //   ]);
    
    $data = JobsRecommendation::find($request->id);
    // $data->city_id = $request->city_id;
    // $data->category_id = $request->category_id;
    $data->jobs_range_salary_id = $request->jobs_range_salary_id;
    $data->save();

    $helper->edit_sub_category($request->arr_sub_category, $data);
    $helper->edit_city($request->arr_city, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/recommendation');
  }

  public function delete(Request $request){
    JobsRecommendation::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/recommendation');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
