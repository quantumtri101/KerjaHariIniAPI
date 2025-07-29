<?php
namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;
use NEWPDF;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\JobsHelper;
use App\Http\Controllers\Helper\Controller\JobsSalaryHelper;

use App\Models\Jobs;
use App\Models\JobsApplication;
use App\Models\JobsRecommendation;
use App\Models\JobsRecommendationCity;
use App\Models\JobsRecommendationSubCategory;
use App\Models\JobsRangeSalary;
use App\Models\JobsSalary;
use App\Models\JobsShift;
use App\Models\JobsApprove;
use App\Models\JobsWorkingArea;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CompanyPosition;
use App\Models\Company;
use App\Models\Education;
use App\Models\Event;
use App\Models\Resume;
use App\Models\Rating;
use App\Models\GeneralQuizResult;
use App\Models\Type;
use App\Models\Province;
use App\Models\City;
use App\Models\CheckLog;
use App\Models\User;

class JobsController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "jobs.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);
    if(!empty($request->api_type) && !empty($request->api_type2) && $request->api_type == "offering" && $request->api_type2 == "recruitScanQR" && count($arr) == 0)
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Pekerjaan tidak ditemukan atau anda belum diundang dalam pekerjaan ini',
      ]);

    $arr_filter = [
      [
        "id" => "staff_approved",
      ],
      [
        "id" => "quota_reached",
      ],
      [
        "id" => "quota_needed",
      ],
      [
        "id" => "app_live",
      ],
      [
        "id" => "job_on_going",
      ],
      [
        "id" => "wait_staff_approve",
      ],
    ];

    foreach($arr as $data)
      $this->relationship_helper->jobs($data);

    $arr_tab = [
      [
        "id" => "on_going",
        "component" => "jobs.component.index_table",
        "url" => url('api/jobs').'?status=not_ended',
      ],
      [
        "id" => "ended",
        "component" => "jobs.component.index_table",
        "url" => url('api/jobs').'?status=ended',
      ],
    ];

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', !empty($request->type) && $request->type == "new" ? 'jobs.index' : 'jobs.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
      'arr_filter' => $arr_filter,
      'arr_tab' => $arr_tab,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();
    $jobs_approve_model = new JobsApprove();
    $jobs_shift_model = new JobsShift();
    $event_model = new Event();
    $jobs_recommendation_model = new JobsRecommendation();
    $jobs_recommendation_city_model = new JobsRecommendationCity();
    $jobs_recommendation_sub_category_model = new JobsRecommendationSubCategory();
    $jobs_range_salary_model = new JobsRangeSalary();
    $category_model = new Category();
    $sub_category_model = new SubCategory();
    $company_model = new Company();
    $city_model = new City();
    $user_model = new User();
    $type_model = new Type();

    $jobs_application_temp = JobsApplication::select('jobs1_id')
      ->selectRaw('MAX(id) as id')
      ->selectRaw('COUNT(id) as total_applicant')
      ->groupBy('jobs1_id');

    $jobs_application_temp1 = JobsApplication::select($jobs_application_model->get_table_name().'.*',)
      ->selectRaw('IF(temp.total_applicant IS NULL, 0, temp.total_applicant) as total_applicant')
      ->joinSub($jobs_application_temp, 'temp', 'temp.id', '=', $jobs_application_model->get_table_name().'.id');

    $jobs_shift_temp = JobsShift::select('jobs1_id')
      ->selectRaw('MIN(end_date) as end_date')
      ->selectRaw('MIN(id) as id')
      ->groupBy('jobs1_id');

    $jobs_shift_temp1 = JobsShift::select($jobs_shift_model->get_table_name().'.*',)
      ->joinSub($jobs_shift_temp, 'temp', 'temp.id', '=', $jobs_shift_model->get_table_name().'.id');



    $arr = Jobs::select($jobs_model->get_table_name().'.*', $category_model->get_table_name().'.name as category_name', $sub_category_model->get_table_name().'.name as sub_category_name', $event_model->get_table_name().'.name as event_name', $company_model->get_table_name().'.name as company_name', $jobs_model->get_table_name().'.staff_type as status_format', $jobs_shift_model->get_table_name().'.start_date as shift_start_date', $jobs_shift_model->get_table_name().'.end_date as shift_end_date',)
      ->selectRaw('IF('.$jobs_application_model->get_table_name().'.total_applicant = '.$jobs_model->get_table_name().'.num_people_required, 1, 0) as is_applicant_full')
      ->join($event_model->get_table_name(), $jobs_model->get_table_name().'.event_id', '=', $event_model->get_table_name().'.id')
      ->join($sub_category_model->get_table_name(), $jobs_model->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
      ->join($category_model->get_table_name(), $sub_category_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id')
      ->join($company_model->get_table_name(), $jobs_model->get_table_name().'.company_id', '=', $company_model->get_table_name().'.id')
      ->leftJoinSub($jobs_application_temp1, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->joinSub($jobs_shift_temp1, $jobs_shift_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      // ->distinct()
      // ->groupBy('id')
      // ->join($city_model->get_table_name(), $jobs_model->get_table_name().'.city_id', '=', $city_model->get_table_name().'.id')
      ->join($user_model->get_table_name(), $jobs_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');
    // dd($arr->get()[1]);

    if(!empty($request->id))
      $arr = $arr->where($jobs_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->worker_id)){
      $application_temp = JobsApplication::select('jobs1_id')
        ->selectRaw('MAX(id) as id')
        ->groupBy('jobs1_id')
        ->where('user_id', '=', $request->worker_id);

      $arr = $arr->joinSub($application_temp, 'customer_application1', 'customer_application1.jobs1_id', '=', $jobs_model->get_table_name().'.id');
    }

    if(isset($request->is_publish))
      $arr = $arr->where($jobs_model->get_table_name().'.is_publish', '=', $request->is_publish);

    if(!empty($request->sub_category_id))
      $arr = $arr->where($jobs_model->get_table_name().'.sub_category_id', '=', $request->sub_category_id);

    if(!empty($request->company_id))
      $arr = $arr->where($jobs_model->get_table_name().'.company_id', '=', $request->company_id);

    if(!empty($request->event_id))
      $arr = $arr->where($jobs_model->get_table_name().'.event_id', '=', $request->event_id);

    if(isset($request->is_live_app))
      $arr = $arr->where($jobs_model->get_table_name().'.is_live_app', '=', $request->is_live_app);

    if(isset($request->is_urgent))
      $arr = $arr->where($jobs_model->get_table_name().'.is_urgent', '=', $request->is_urgent);

    if(isset($request->is_approve))
      $arr = $arr->where($jobs_model->get_table_name().'.is_approve', '=', $request->is_approve);

    if(isset($request->staff_type))
      $arr = $arr->where($jobs_model->get_table_name().'.staff_type', '=', $request->staff_type);

    if(!empty($request->status)){
      if($request->status == 'not_ended')
        $arr = $arr->where($jobs_model->get_table_name().'.status', '!=', 'ended');
      else
        $arr = $arr->where($jobs_model->get_table_name().'.status', '=', $request->status);
    }

    if(!empty($request->name))
      $arr = $arr->where($jobs_model->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->filter)){
      if($request->filter == "wait_staff_approve"){
        $jobs_approve = JobsApprove::where('status_approve', '!=', 'approved');

        $arr = $arr->joinSub($jobs_approve, 'jobs_approve1', 'jobs_approve1.jobs1_id', '=', $jobs_model->get_table_name().'.id');
      }
      else if($request->filter == "staff_approved"){
        $jobs_approve = JobsApprove::where('status_approve', '!=', 'approved');

        $arr = $arr->leftJoinSub($jobs_approve, 'jobs_approve1', 'jobs_approve1.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->whereNull('jobs_approve1.id');
      }
      else if($request->filter == "quota_reached"){
        $application_temp = JobsApplication::select($jobs_application_model->get_table_name().'.*')
          ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
          ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id');

        $arr = $arr->selectRaw('COUNT(jobs_application1.id) as total_application')
          ->groupBy('id', 'total_applicant')
          ->leftJoinSub($application_temp, 'jobs_application1', 'jobs_application1.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->havingRaw('total_application = num_people_required');;
      }
      else if($request->filter == "quota_needed"){
        $application_temp = JobsApplication::select($jobs_application_model->get_table_name().'.*')
          ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
          ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id');

        $arr = $arr->selectRaw('COUNT(jobs_application1.id) as total_application')
          ->groupBy('id', 'total_applicant')
          ->leftJoinSub($application_temp, 'jobs_application1', 'jobs_application1.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->havingRaw('total_application < num_people_required');
      }
      else if($request->filter == "app_live"){
        $arr = $arr->where($jobs_model->get_table_name().'.is_live_app', '=', 1);
      }
      else if($request->filter == "job_on_going"){
        $jobs_shift = JobsShift::where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now());
        $arr = $arr->joinSub($jobs_shift, 'jobs_shift_temp', 'jobs_shift_temp.jobs1_id', '=', $jobs_model->get_table_name().'.id');
      }
      else if($request->filter == "job_not_started"){
        $jobs_shift = JobsShift::where('end_date', '>=', Carbon::now());
        $arr = $arr->joinSub($jobs_shift, 'jobs_shift_temp', 'jobs_shift_temp.jobs1_id', '=', $jobs_model->get_table_name().'.id');
      }
    }

    if(!empty($request->jobs_recommendation_id)){
      $jobs_recommendation = JobsRecommendation::find($request->jobs_recommendation_id);

      $arr = $arr->join($jobs_recommendation_city_model->get_table_name(), function($join) use($jobs_recommendation_city_model, $jobs_recommendation_model, $jobs_model, $jobs_recommendation){
          $join = $join->whereNotNull($jobs_recommendation_city_model->get_table_name().'.deleted_at');

          $join = $join->where(function($where) use($jobs_recommendation, $jobs_model) {
            foreach($jobs_recommendation->city as $city)
              $join = $join->orWhere($jobs_model->get_table_name().'.city_id', '=', $city->id);
          });
        })
        ->join($jobs_recommendation_sub_category_model->get_table_name(), function($join) use($jobs_recommendation_sub_category_model, $jobs_recommendation_model, $jobs_model, $jobs_recommendation){
          $join = $join->whereNotNull($jobs_recommendation_sub_category_model->get_table_name().'.deleted_at');

          $join = $join->where(function($where) use($jobs_recommendation, $jobs_model) {
            foreach($jobs_recommendation->sub_category as $sub_category)
              $join = $join->orWhere($jobs_model->get_table_name().'.sub_category_id', '=', $sub_category->id);
          });
        });

      if(!empty($jobs_recommendation->range_salary))
        $arr = $arr->join($jobs_range_salary_model->get_table_name(), function($join) use($jobs_range_salary_model, $jobs_recommendation_model, $jobs_model, $jobs_recommendation){
          $join = $join->where($jobs_model->get_table_name().'.salary_casual', '>=', $jobs_recommendation->range_salary->min_salary)
            ->where($jobs_model->get_table_name().'.salary_casual', '<=', $jobs_recommendation->range_salary->max_salary)
            ->whereNotNull($jobs_range_salary_model->get_table_name().'.deleted_at');
        });
    }

    if(!empty($request->api_type)){
      if($request->api_type == "recommendation"){
        if(Auth::check() && Auth::user()->type->name == "customer_oncall"){
          // $recommendation_city_temp = JobsRecommendationCity::select($jobs_recommendation_city_model->get_table_name().'.jobs_recommendation_id')
          //   ->selectRaw('MAX('.$jobs_recommendation_city_model->get_table_name().'.id) as id')
          //   ->join($jobs_recommendation_model->get_table_name(), $jobs_recommendation_city_model->get_table_name().'.jobs_recommendation_id', '=', $jobs_recommendation_model->get_table_name().'.id')
          //   ->groupBy('jobs_recommendation_id')
          //   ->where($jobs_recommendation_model->get_table_name().'.user_id', '=', Auth::user()->id);

          // $recommendation_city_temp1 = JobsRecommendationCity::select($jobs_recommendation_city_model->get_table_name().'.*')
          //   ->joinSub($recommendation_city_temp, 'temp', 'temp.id', '=', $jobs_recommendation_city_model->get_table_name().'.id');

          // $recommendation_sub_category_temp = JobsRecommendationSubCategory::select($jobs_recommendation_sub_category_model->get_table_name().'.jobs_recommendation_id')
          //   ->selectRaw('MAX('.$jobs_recommendation_sub_category_model->get_table_name().'.id) as id')
          //   ->join($jobs_recommendation_model->get_table_name(), $jobs_recommendation_sub_category_model->get_table_name().'.jobs_recommendation_id', '=', $jobs_recommendation_model->get_table_name().'.id')
          //   ->groupBy('jobs_recommendation_id')
          //   ->where($jobs_recommendation_model->get_table_name().'.user_id', '=', Auth::user()->id);

          // $recommendation_sub_category_temp1 = JobsRecommendationSubCategory::select($jobs_recommendation_sub_category_model->get_table_name().'.*')
          //   ->joinSub($recommendation_sub_category_temp, 'temp', 'temp.id', '=', $jobs_recommendation_sub_category_model->get_table_name().'.id');

          // $recommendation_temp = JobsRecommendation::select($jobs_recommendation_model->get_table_name().'.user_id')
          //   ->selectRaw('MAX('.$jobs_recommendation_model->get_table_name().'.id) as id')
          //   ->groupBy('user_id')
          //   ->where($jobs_recommendation_model->get_table_name().'.user_id', '=', Auth::user()->id);

          // $recommendation_temp1 = JobsRecommendation::select($jobs_recommendation_model->get_table_name().'.*')
          //   ->joinSub($recommendation_temp, 'temp', 'temp.id', '=', $jobs_recommendation_model->get_table_name().'.id');

          $jobs_recommendation_city_temp = JobsRecommendationCity::select('jobs_recommendation_id')
            ->selectRaw('MAX(id) as id')
            ->groupBy('jobs_recommendation_id');

          $jobs_recommendation_city_temp1 = JobsRecommendationCity::select($jobs_recommendation_city_model->get_table_name().'.*')
            ->joinSub($jobs_recommendation_city_temp, 'temp', $jobs_recommendation_city_model->get_table_name().'.id', '=', 'temp.id');

          $application_temp = JobsApplication::where('user_id', '=', Auth::user()->id)
            ->where('status', '!=', 'declined');



          $arr = $arr->selectRaw('IF('.$jobs_recommendation_sub_category_model->get_table_name().'.id IS NOT NULL AND '.$jobs_recommendation_city_model->get_table_name().'.id IS NOT NULL AND '.$jobs_range_salary_model->get_table_name().'.id IS NOT NULL, 10, 0) as order_recommendation')
            ->leftJoin($jobs_recommendation_model->get_table_name(), function($join) use($jobs_recommendation_model, $jobs_recommendation_sub_category_model, $jobs_recommendation_city_model){
              $join = $join->where($jobs_recommendation_model->get_table_name().'.user_id', '=', Auth::user()->id)
                ->whereNotNull($jobs_recommendation_model->get_table_name().'.deleted_at');
            })
            ->leftJoin($jobs_recommendation_city_model->get_table_name(), function($join) use($jobs_recommendation_city_model, $jobs_recommendation_model, $jobs_model){
              $join = $join->on($jobs_recommendation_city_model->get_table_name().'.jobs_recommendation_id', '=', $jobs_recommendation_model->get_table_name().'.id')
                ->on($jobs_model->get_table_name().'.city_id', '=', $jobs_recommendation_city_model->get_table_name().'.city_id')
                ->whereNotNull($jobs_recommendation_city_model->get_table_name().'.deleted_at');
            })
            ->leftJoin($jobs_recommendation_sub_category_model->get_table_name(), function($join) use($jobs_recommendation_sub_category_model, $jobs_recommendation_model, $jobs_model){
              $join = $join->on($jobs_recommendation_sub_category_model->get_table_name().'.jobs_recommendation_id', '=', $jobs_recommendation_model->get_table_name().'.id')
                ->on($jobs_model->get_table_name().'.sub_category_id', '=', $jobs_recommendation_sub_category_model->get_table_name().'.sub_category_id')
                ->whereNotNull($jobs_recommendation_sub_category_model->get_table_name().'.deleted_at');
            })
            ->leftJoin($jobs_range_salary_model->get_table_name(), function($join) use($jobs_range_salary_model, $jobs_recommendation_model, $jobs_model){
              $join = $join->on($jobs_recommendation_model->get_table_name().'.jobs_range_salary_id', '=', $jobs_range_salary_model->get_table_name().'.id')
                ->on($jobs_model->get_table_name().'.salary_casual', '>=', $jobs_range_salary_model->get_table_name().'.min_salary')
                ->on($jobs_model->get_table_name().'.salary_casual', '<=', $jobs_range_salary_model->get_table_name().'.max_salary')
                ->whereNotNull($jobs_range_salary_model->get_table_name().'.deleted_at');
            })
            ->leftJoinSub($application_temp, 'customer_application', 'customer_application.jobs1_id', '=', $jobs_model->get_table_name().'.id')
            ->orderBy('order_recommendation', 'desc')
            ->whereRaw('IF(('.$jobs_application_model->get_table_name().'.total_applicant = '.$jobs_model->get_table_name().'.num_people_required AND customer_application.id IS NOT NULL) OR '.$jobs_application_model->get_table_name().'.total_applicant IS NULL, 1, 0) = ?', [1,]);
        }
      }
      else if($request->api_type == "offering"){
        if(Auth::check() && Auth::user()->type->name == "customer_oncall"){
          $application_temp = JobsApplication::select('jobs1_id')
            ->selectRaw('MAX(id) as id')
            ->groupBy('jobs1_id')
            ->where('user_id', '=', Auth::user()->id)
            ->where('is_approve_corp', '=', 1)
            ->where('is_approve_worker', '=', 0);

          $arr = $arr->joinSub($application_temp, 'jobs_application1', 'jobs_application1.jobs1_id', '=', $jobs_model->get_table_name().'.id');
        }
      }
    }
    // dd($arr->toSql());

    if(empty($request->id) && empty($request->user_id) && Auth::check() && (Auth::user()->type->name == 'staff' || Auth::user()->type->name == 'RO'))
      $arr = $arr->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id);

    // if(empty($request->id) && empty($request->user_id) && Auth::check() && Auth::user()->type->name == 'customer_oncall'){
    //   $application_temp = JobsApplication::select('jobs1_id')
    //     ->selectRaw('MAX(id) as id')
    //     ->groupBy('jobs1_id')
    //     ->where('user_id', '=', Auth::user()->id);

    //   $arr = $arr->leftJoinSub($application_temp, $jobs_application_model->get_table_name(), function($join) use($jobs_application_model, $jobs_model){
    //     $join = $join->on($jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id');
    //   })
    //     ->whereNull($jobs_application_model->get_table_name().'.id');
    // }

    if(empty($request->id) && empty($request->user_id) && Auth::check() && Auth::user()->type->name == 'staff'){
      $jobs_approve_temp = JobsApprove::select('jobs1_id')
        ->selectRaw('MAX(id) as id')
        ->groupBy('jobs1_id')
        ->where('user_id', '=', Auth::user()->id);

      $arr = $arr->joinSub($jobs_approve_temp, $jobs_approve_model->get_table_name(), $jobs_approve_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id');
    }

    if(empty($request->order))
      $arr = $arr->orderBy('created_at', 'desc');
    // dd($arr->getBindings());

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $arr_category = Category::all();
    $arr_province = Province::all();
    $arr_sub_category = [];
    $arr_city = [];
    $arr_working_area = City::all();
    if(!empty(Auth::user()->company))
      $arr_sub_category = SubCategory::where('category_id', '=', Auth::user()->company->category->id)->get();
    $arr_company = Company::all();

    $arr_event = Event::where('end_date', '>=', Carbon::now());
    // $arr_event = new Event();
    if(!empty(Auth::user()->company))
      $arr_event = $arr_event->where('company_id', '=', Auth::user()->company->id);
    $arr_event = $arr_event->get();

    $arr_company_position = CompanyPosition::all();
    $arr_education = Education::all();

    $type = Type::where('name', '=', 'staff')->first();
    $arr_staff = User::where('type_id', '=', $type->id);
    if(!empty(Auth::user()->company))
      $arr_staff = $arr_staff->where('company_id', '=', Auth::user()->company->id);
    $arr_staff = $arr_staff->get();
    $data = null;
    // $lang_file = "recruit_form";
    $lang_file = "jobs";
    if(!empty($request->id)){
      $data = Jobs::find($request->id);
      $arr_sub_category = SubCategory::where('category_id', '=', $data->sub_category->category->id)->get();

      $jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
        ->where('sort_order', '=', 1)
        ->first();
      $data->allow_edit = !empty($jobs_approve) && $jobs_approve->status_approve != "approved";

      $arr_city = City::where('province_id', '=', $data->city->province->id)->get();

      $shift = JobsShift::where('jobs1_id', '=', $data->id)
        ->where('start_date', '<=', Carbon::now())
        ->first();
      $data->already_working = !empty($shift);

      foreach($arr_working_area as $key => $working_area){
        foreach($data->working_area as $working_area1){
          if($working_area->id == $working_area1->city->id){
            $arr_working_area[$key]->is_selected = true;
            break;
          }
        }
      }

      if(!$data->allow_edit){
        $arr_approve = [];
        $arr_approve_check_log = [];
        $arr_approve_salary = [];
        $arr_working_area_temp = [];

        foreach($data->approve as $approve)
          array_push($arr_approve, [
            "user_id" => $approve->user->id,
          ]);

        foreach($data->approve_check_log as $approve_check_log)
          array_push($arr_approve_check_log, [
            "user_id" => $approve_check_log->user->id,
          ]);

        foreach($data->approve_salary as $approve_salary)
          array_push($arr_approve_salary, [
            "user_id" => $approve_salary->user->id,
          ]);

        foreach($data->working_area as $working_area)
          array_push($arr_working_area_temp, $working_area->city->id);

        $data->arr_approve = $arr_approve;
        $data->arr_approve_check_log = $arr_approve_check_log;
        $data->arr_approve_salary = $arr_approve_salary;
        $data->arr_working_area = $arr_working_area_temp;
      }


      // dd($arr_working_area);

      // $lang_file = $data->is_approve == 1 ? "jobs" : "recruit_form";

      // foreach($arr_staff as $key => $staff){
      //   $jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
      //     ->where('user_id', '=', $staff->id)
      //     ->first();
      //   $arr_staff[$key]->is_checked = !empty($jobs_approve);
      // }
      // dd($data->shift);
    }

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "jobs.component.action.general_info",
      ],
      [
        "id" => "jobs_info",
        "component" => "jobs.component.action.jobs_info",
      ],
      [
        "id" => "criteria_info",
        "component" => "jobs.component.action.criteria_info",
      ],
      [
        "id" => "qualification_info",
        "component" => "jobs.component.action.qualification_info",
      ],
      [
        "id" => "briefing_interview_info",
        "component" => "jobs.component.action.briefing_interview_info",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.action', [
      'jobs' => $data,
      'arr_category' => $arr_category,
      'arr_sub_category' => $arr_sub_category,
      'arr_province' => $arr_province,
      'arr_city' => $arr_city,
      'arr_event' => $arr_event,
      'arr_company' => $arr_company,
      'arr_company_position' => $arr_company_position,
      'arr_staff' => $arr_staff,
      'arr_education' => $arr_education,
      'arr_tab' => $arr_tab,
      'arr_working_area' => $arr_working_area,
      'lang_file' => $lang_file,
    ]);
  }

  public function export_jobs_approve_pdf(Request $request){
    $jobs = Jobs::find($request->id);

    $cc_string = "";
    foreach($jobs->approve as $key => $approve){
      $cc_string .= $approve->user->name . ' sebagai ' . $approve->user->company_position->name;
      if($key < count($jobs->approve) - 1)
        $cc_string .= ", ";
    }
    $jobs->cc_string = $cc_string;

    $dompdf = NEWPDF::loadHTML(view('exports.jobs_pdf', [
      'jobs' => $jobs,
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('jobs.pdf');

    return $this->get_data_helper->return_data($request, [], 'view', 'exports.jobs_pdf', [
      'jobs' => $jobs,
    ]);
  }

  public function choose_staff(Request $request){
    // $lang_file = "recruit_form";
    $lang_file = "jobs";
    $data = Jobs::find($request->id);
    $this->relationship_helper->jobs($data);

    $arr_tab = [
      [
        "id" => "list_user_regular",
        "component" => "jobs.component.choose_staff.list_user_regular",
      ],
      [
        "id" => "list_user_casual",
        "component" => "jobs.component.choose_staff.list_user_casual",
      ],
      [
        "id" => "list_user_casual_all",
        "component" => "jobs.component.choose_staff.list_user_casual_all",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.choose_staff', [
      'jobs' => $data,
      'arr_tab' => $arr_tab,
      'lang_file' => $lang_file,
    ]);
  }

  public function print_qr(Request $request){
    $data = Jobs::find($request->id);
    $jobs_shift = JobsShift::where('jobs1_id', '=', $data->id)
      // ->where('end_date', '>', Carbon::now()->formatLocalized('%Y-%m-%d'))
      ->orderBy('start_date', 'asc')
      ->first();

    // dd(QrCode::format('eps')->size(100)->generate($data->id.' '.Auth::user()->id));
    // return $this->get_data_helper->return_data($request, [], 'view', 'jobs.component.print_qr', [
    //   'jobs' => $data,
    // ]);
    $dompdf = NEWPDF::loadHTML(view('jobs.component.print_qr', [
      'jobs' => $data,
      'jobs_shift' => $jobs_shift,
      'qr_code' => base64_encode(QrCode::format('svg')->size(300)->generate($data->id)),
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('report.pdf');
  }

  public function detail(Request $request){
    $data = Jobs::find($request->id);
    $jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
      ->where('user_id', '=', Auth::user()->id)
      ->first();
    $arr_jobs_working_area = JobsWorkingArea::where('jobs1_id', '=', $data->id)->get();
    $working_area_str = "";
    foreach($arr_jobs_working_area as $key => $working_area)
      $working_area_str .= $working_area->city->name.($key < count($arr_jobs_working_area) - 1 ? ', ' : '');
    $data->working_area_str = $working_area_str;
    if(!empty($jobs_approve) && $jobs_approve->sort_order > 1)
      $before_jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
        ->where('sort_order', '=', $jobs_approve->sort_order - 1)
        ->first();
    $data->allow_approve = Auth::user()->type->name == "staff" && $jobs_approve->status_approve == 'not_yet_approved' && ((!empty($before_jobs_approve) && $before_jobs_approve->status_approve == 'approved') || empty($before_jobs_approve));
    $this->relationship_helper->jobs($data, $request);

    // $lang_file = $data->is_approve == 1 ? "jobs" : "recruit_form";
    $lang_file = "jobs";
    $type = Type::where('name', '=', 'customer_oncall')->first();
    $arr_rating = Rating::where('jobs1_id', '=', $data->id)->get();
    $arr_staff = User::where(function($where) use($data){
        foreach($data->application as $jobs_application)
          $where = $where->orWhere('id', '=', $jobs_application->user->id);
      })
      ->where(function($where) use($arr_rating){
        foreach($arr_rating as $rating)
          $where = $where->where('id', '!=', $rating->staff->id);
      })
      ->where('type_id', '=', $type->id)
      ->get();
    // dd($arr_staff);



    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "jobs.component.general_info",
      ],
      [
        "id" => "job_description",
        "component" => "jobs.component.job_description",
      ],
      [
        "id" => "user_approval",
        "component" => "jobs.component.user_approval",
      ],
      [
        "id" => "list_rating",
        "component" => "jobs.component.list_rating",
      ],
      [
        "id" => "list_application",
        "component" => "jobs.component.list_application",
      ],
      // [
      //   "id" => "list_salary",
      //   "component" => "jobs.component.list_salary",
      // ],
      // [
      //   "id" => "list_image",
      //   "component" => "jobs.component.list_image",
      // ],
      // [
      //   "id" => "list_qualification",
      //   "component" => "jobs.component.list_qualification",
      // ],
      // [
      //   "id" => "criteria_data",
      //   "component" => "jobs.component.criteria_data",
      // ],
      // [
      //   "id" => "briefing_data",
      //   "component" => "jobs.component.briefing_data",
      // ],
      // [
      //   "id" => "interview_data",
      //   "component" => "jobs.component.interview_data",
      // ],
      // [
      //   "id" => "list_document",
      //   "component" => "jobs.component.list_document",
      // ],
      // [
      //   "id" => "list_approve",
      //   "component" => "jobs.component.list_approve",
      // ],
      [
        "id" => "list_shift",
        "component" => "jobs.component.list_shift",
      ],
      // [
      //   "id" => "list_approve_check_log",
      //   "component" => "jobs.component.list_approve_check_log",
      // ],
      // [
      //   "id" => "list_approve_salary",
      //   "component" => "jobs.component.list_approve_salary",
      // ],
      // [
      //   "id" => "list_rating",
      //   "component" => "jobs.component.list_rating",
      // ],
      // [
      //   "id" => "list_check_log",
      //   "component" => "jobs.component.list_check_log",
      // ],
    ];

    // if(Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO")
      // array_push($arr_tab,
    //     // [
    //     //   "id" => "list_application",
    //     //   "component" => "jobs.component.list_application",
    //     // ],
    //     // [
    //     //   "id" => "list_user_regular",
    //     //   "component" => "jobs.component.list_user_regular",
    //     // ],
    //     // [
    //     //   "id" => "list_user_casual",
    //     //   "component" => "jobs.component.list_user_casual",
    //     // ],
      //   [
      //     "id" => "list_application",
      //     "component" => "jobs.component.list_application",
      //   ],
      // );
    // else if(Auth::user()->type->name == "staff" && Auth::user()->company_position->name == "HRD" && $data->is_approve == 1){
    //   array_push($arr_tab,

    //     [
    //       "id" => "list_check_log",
    //       "component" => "jobs.component.list_check_log",
    //     ],
    //   );
    // }

    return $this->get_data_helper->return_data($request, [], 'view', 'jobs.detail', [
      'jobs' => $data,
      'arr_tab' => $arr_tab,
      'arr_staff' => $arr_staff,
      'lang_file' => $lang_file,
    ]);
  }

  public function post(Request $request){
    $helper = new JobsHelper();
    // dd($request->all());

    if(!empty($request->event_id))
      $event = Event::find($request->event_id);
    else if(!empty($request->company_id))
      $company = Company::find($request->company_id);

    $data = new Jobs();
    $data->event_id = !empty($event) ? $event->id : null;
    $data->company_id = !empty($event) ? $event->company->id : $company->id;
    $data->city_id = $request->city_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->company_position_id = $request->company_position_id;
    $data->user_id = Auth::user()->id;
    $data->name = $request->name;
    $data->description = $request->description;
    $data->is_urgent = $request->is_urgent;
    $data->staff_type = $request->staff_type;
    $data->num_people_required = str_replace('.', '', $request->num_people_required);
    $data->salary_type_regular = $request->salary_type_regular;
    $data->salary_regular = str_replace('.', '', $request->salary_regular);
    $data->salary_type_casual = $request->salary_type_casual;
    $data->salary_casual = str_replace('.', '', $request->salary_casual);
    $data->benefit = $request->benefit;
    $data->save();

    // $arr_image = json_decode($request->arr_image, true);
    // $helper->edit_image($arr_image, $data);
    $arr_approve = [];
    $arr_approve_check_log = [];
    $arr_approve_salary = [];
    $arr_shift = [];
    $arr_qualification = [];
    $arr_image = json_decode($request->arr_image, true);
    if($request->is_split_shift == 1){
      for($x = 1; $x <= 2; $x++){
        $arr1 = [
          "start_date" => Carbon::createFromFormat('d-m-Y H:i', $request->{'start_time'.$x}),
          "end_date" => Carbon::createFromFormat('d-m-Y H:i', $request->{'end_time'.$x}),
        ];
        array_push($arr_shift, $arr1);
      }
    }
    else{
      $arr1 = [
        "start_date" => Carbon::createFromFormat('d-m-Y H:i', $request->start_date),
        "end_date" => Carbon::createFromFormat('d-m-Y H:i', $request->end_date),
      ];
      array_push($arr_shift, $arr1);
    }
    foreach($request->arr_people_approve as $user)
      array_push($arr_approve, [
        "user_id" => $user,
      ]);
    foreach($request->arr_people_approve_check_log as $user)
      array_push($arr_approve_check_log, [
        "user_id" => $user,
      ]);
    foreach($request->arr_people_approve_salary as $user)
      array_push($arr_approve_salary, [
        "user_id" => $user,
      ]);
    $arr_qualification = json_decode($request->arr_qualification, true);
    $arr_working_area = $request->working_area;

    $helper->edit_shift($arr_shift, $data);
    $helper->edit_criteria($request, $data);
    if($request->need_briefing == 1)
      $helper->edit_briefing($request, $data);
    if($request->need_interview == 1)
      $helper->edit_interview($request, $data);
    $helper->edit_qualification($arr_qualification, $data);
    $helper->edit_approve($arr_approve, $data);
    $helper->edit_working_area($arr_working_area, $data);
    $helper->edit_approve_check_log($arr_approve_check_log, $data);
    $helper->edit_approve_salary($arr_approve_salary, $data);
    $helper->edit_image($arr_image, $data);

    // dd($request->all());
    if($request->type == "save_publish"){
      $data->publish_start_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_start_date);
      $data->publish_end_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_end_date);
      $data->is_live_app = $data->is_approve == 1 && $data->publish_start_at <= Carbon::now() && $data->publish_end_at >= Carbon::now() ? 1 : 0;
      $data->save();
    }
    else if($data->shift[0]->start_date->diffInDays(Carbon::now()) == 0){
      $data->publish_start_at = Carbon::now();
      $data->publish_end_at = $data->shift[0]->start_date;
      $data->is_live_app = $data->is_approve;
      $data->save();
    }


    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs');
  }

  public function change_live_app(Request $request){
    $jobs = Jobs::find($request->jobs_id);

    if(isset($request->is_live_app) && $request->is_live_app == "0"){
      $jobs->is_live_app = 0;
      $jobs->publish_start_at = null;
      $jobs->publish_end_at = null;
    }

    if(!empty($request->publish_start_date))
      $jobs->publish_start_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_start_date);
    if(!empty($request->publish_end_date))
      $jobs->publish_end_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_end_date);

    if(!empty($jobs->publish_start_at) && !empty($jobs->publish_end_at) && $jobs->publish_start_at <= Carbon::now() && $jobs->publish_end_at >= Carbon::now())
      $jobs->is_live_app = 1;
    $jobs->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function add_choose_staff(Request $request){

    $helper = new JobsHelper();

    $jobs = Jobs::find($request->jobs_id);
    $arr_user_regular = json_decode($request->arr_user_regular, true);
    $arr_user_casual = json_decode($request->arr_user_casual, true);
    $arr_user_casual_all = json_decode($request->arr_user_casual_all, true);
    $total_user = 0;

    foreach($jobs->shift as $shift){
      if(!empty($arr_user_regular)){
        $response_regular = $helper->check_working_time_all_user($shift, $arr_user_regular, "regular");
        if(!empty($response_regular))
          return $this->get_data_helper->return_data($request, $response_regular, 'back');
      }
      if(!empty($arr_user_casual)){
        $response_casual = $helper->check_working_time_all_user($shift, $arr_user_casual, "casual");
        if(!empty($response_casual))
          return $this->get_data_helper->return_data($request, $response_casual, 'back');
      }
      if(!empty($arr_user_casual_all)){
        $response_casual_all = $helper->check_working_time_all_user($shift, $arr_user_casual_all, "casual");
        if(!empty($response_casual_all))
          return $this->get_data_helper->return_data($request, $response_casual_all, 'back');
      }
    }
    // dd($arr_user_casual);

    $arr_temp = JobsApplication::where(function($where) use($arr_user_regular, $arr_user_casual){
      if(!empty($arr_user_regular)){
        foreach($arr_user_regular as $temp){
          if(!empty($temp['jobs_application_id']))
            $where = $where->where('id','!=',$temp['jobs_application_id']);
        }
      }
      if(!empty($arr_user_casual)){
        foreach($arr_user_casual as $temp){
          if(!empty($temp['jobs_application_id']))
            $where = $where->where('id','!=',$temp['jobs_application_id']);
        }
      }
      if(!empty($arr_user_casual_all)){
        foreach($arr_user_casual_all as $temp){
          if(!empty($temp['jobs_application_id']))
            $where = $where->where('id','!=',$temp['jobs_application_id']);
        }
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    if(!empty($arr_user_regular)){
      $total_user += count($arr_user_regular);
      foreach($arr_user_regular as $user_regular){
        $user = User::find($user_regular["id"]);
        $resume = Resume::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();
        $general_quiz_result = GeneralQuizResult::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();

        if(!empty($user_regular['jobs_application_id']))
          $data = JobsApplication::find($user_regular['jobs_application_id']);
        if(empty($data))
          $data = new JobsApplication();
        $data->user_id = $user->id;
        $data->resume_id = !empty($resume) ? $resume->id : null;
        $data->general_quiz_result_id = !empty($general_quiz_result) ? $general_quiz_result->id : null;
        $data->jobs1_id = $jobs->id;
        $data->is_approve_corp = 1;
        $data->salary_approve = $data->user->type->name == "customer_regular" ? $jobs->salary_regular : $jobs->salary_casual;
        $data->salary_init = $data->salary_approve;
        $data->status = "accepted";
        $data->save();

        $this->communication_helper->send_push_notif($user, 'Rekomendasi Pekerjaan', 'Anda telah mendapatkan Rekomendasi Pekerjaan, dari '.$data->jobs->company->name.' untuk Pekerjaan '.$data->jobs->name, ["id" => $data->jobs->id, 'type' => "jobs_offer"]);

        $data = null;
      }
    }

    if(!empty($arr_user_casual)){
      $total_user += count($arr_user_casual);

      foreach($arr_user_casual as $user_casual){
        $user = User::find($user_casual["id"]);
        $resume = Resume::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();
        $general_quiz_result = GeneralQuizResult::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();

        if(!empty($user_casual['jobs_application_id']))
          $data = JobsApplication::find($user_casual['jobs_application_id']);
        if(empty($data))
          $data = new JobsApplication();
        $data->user_id = $user->id;
        $data->resume_id = !empty($resume) ? $resume->id : null;
        $data->general_quiz_result_id = !empty($general_quiz_result) ? $general_quiz_result->id : null;
        $data->jobs1_id = $jobs->id;
        $data->is_approve_corp = 1;
        $data->salary_approve = $data->user->type->name == "customer_regular" ? $jobs->salary_regular : $jobs->salary_casual;
        $data->salary_init = $data->salary_approve;
        $data->save();

        $this->communication_helper->send_push_notif($user, 'Rekomendasi Pekerjaan', 'Anda telah mendapatkan Rekomendasi Pekerjaan, dari '.$data->jobs->company->name.' untuk Pekerjaan '.$data->jobs->name, ["id" => $data->jobs->id, 'type' => "jobs_offer"]);

        $data = null;
      }
    }

    if(!empty($arr_user_casual_all)){
      $total_user += count($arr_user_casual_all);

      foreach($arr_user_casual_all as $user_casual_all){
        $user = User::find($user_casual_all["id"]);
        $resume = Resume::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();
        $general_quiz_result = GeneralQuizResult::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();

        if(!empty($user_casual_all['jobs_application_id']))
          $data = JobsApplication::find($user_casual_all['jobs_application_id']);
        if(empty($data))
          $data = new JobsApplication();
        $data->user_id = $user->id;
        $data->resume_id = !empty($resume) ? $resume->id : null;
        $data->general_quiz_result_id = !empty($general_quiz_result) ? $general_quiz_result->id : null;
        $data->jobs1_id = $jobs->id;
        $data->is_approve_corp = 1;
        $data->salary_approve = $data->user->type->name == "customer_regular" ? $jobs->salary_regular : $jobs->salary_casual;
        $data->salary_init = $data->salary_approve;
        $data->save();

        $data = null;
      }
    }

    // $jobs->is_approve = count($jobs->application) > 0 ? 1 : 0;
    // if(!empty($request->publish_date))
    //   $jobs->publish_at = Carbon::createFromFormat('d/m/Y H:i:s', $request->publish_date.' 00:00:00');
    // else if(count($jobs->application) == $jobs->num_people_required)
    //   $jobs->publish_at = Carbon::now();
    $jobs->publish_start_at = Carbon::now();
    $jobs->publish_end_at = $jobs->shift[0]->start_date;
    $jobs->is_live_app = 1;
    $jobs->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs');
  }

  public function put(Request $request){
    $helper = new JobsHelper();
    // dd($request->all());

    if(!empty($request->event_id))
      $event = Event::find($request->event_id);
    else if(!empty($request->company_id))
      $company = Company::find($request->company_id);

    $data = Jobs::find($request->id);
    $data->event_id = !empty($event) ? $event->id : null;
    $data->company_id = !empty($event) ? $event->company->id : $company->id;
    $data->city_id = $request->city_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->company_position_id = $request->company_position_id;
    $data->user_id = Auth::user()->id;
    $data->name = $request->name;
    $data->description = $request->description;
    $data->is_urgent = $request->is_urgent;
    $data->staff_type = $request->staff_type;
    $data->num_people_required = str_replace('.', '', $request->num_people_required);
    $data->salary_type_regular = $request->salary_type_regular;
    $data->salary_regular = str_replace('.', '', $request->salary_regular);
    $data->salary_type_casual = $request->salary_type_casual;
    $data->salary_casual = str_replace('.', '', $request->salary_casual);
    $data->benefit = $request->benefit;
    $data->save();

    // $arr_image = json_decode($request->arr_image, true);
    // $helper->edit_image($arr_image, $data);
    $arr_approve = [];
    $arr_approve_check_log = [];
    $arr_approve_salary = [];
    $arr_shift = [];
    $arr_qualification = [];
    $arr_image = json_decode($request->arr_image, true);
    // dd($arr_image);
    if($request->is_split_shift == 1){
      for($x = 1; $x <= 2; $x++){
        $arr1 = [
          "start_date" => Carbon::createFromFormat('d-m-Y H:i', $request->{'start_time'.$x}),
          "end_date" => Carbon::createFromFormat('d-m-Y H:i', $request->{'end_time'.$x}),
        ];
        array_push($arr_shift, $arr1);
      }
    }
    else{
      $arr1 = [
        "start_date" => Carbon::createFromFormat('d-m-Y H:i', $request->start_date),
        "end_date" => Carbon::createFromFormat('d-m-Y H:i', $request->end_date),
      ];
      array_push($arr_shift, $arr1);
    }
    if(!empty($request->arr_people_approve))
      foreach($request->arr_people_approve as $user)
        array_push($arr_approve, [
          "user_id" => $user,
        ]);
    else
      $arr_approve = json_decode($request->arr_approve_json, true);
    if(!empty($request->arr_people_approve_check_log))
      foreach($request->arr_people_approve_check_log as $user)
        array_push($arr_approve_check_log, [
          "user_id" => $user,
        ]);
    else
      $arr_approve_check_log = json_decode($request->arr_approve_check_log_json, true);
    if(!empty($request->arr_people_approve_salary))
      foreach($request->arr_people_approve_salary as $user)
        array_push($arr_approve_salary, [
          "user_id" => $user,
        ]);
    else
      $arr_approve_salary = json_decode($request->arr_approve_salary_json, true);
    $arr_qualification = json_decode($request->arr_qualification, true);
    $arr_working_area = !empty($request->working_area) ? $request->working_area : json_decode($request->arr_working_area_json, true);

    $helper->edit_shift($arr_shift, $data);
    $helper->edit_criteria($request, $data);
    if($request->need_briefing == 1)
      $helper->edit_briefing($request, $data);
    if($request->need_interview == 1)
      $helper->edit_interview($request, $data);
    $helper->edit_qualification($arr_qualification, $data);
    $helper->edit_approve($arr_approve, $data);
    $helper->edit_working_area($arr_working_area, $data);
    $helper->edit_approve_check_log($arr_approve_check_log, $data);
    $helper->edit_approve_salary($arr_approve_salary, $data);
    $helper->edit_image($arr_image, $data);

    if($data->is_approve == 0){
      $jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
        ->orderBy('sort_order', 'asc')
        ->first();
      if($jobs_approve->status_approve == "declined"){
        $jobs_approve->status_approve = "not_yet_approved";
        $jobs_approve->decline_reason = null;
        $jobs_approve->save();
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs/detail?id='.$data->id);
  }

  public function add_staff_ots(Request $request){
    $jobs = Jobs::find($request->jobs_id);
    $jobs_shift = JobsShift::where('jobs1_id', '=', $jobs->id)->orderBy('start_date', 'asc')->first();
    $user = User::find($request->qr_content);
    if(empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User not Found',
      ], 'back');
    else if(!empty($user) && $user->type->name != "customer_oncall" && $user->type->name != "customer_regular")
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User must be Staff',
      ], 'back');
    $jobs_application = JobsApplication::where('user_id', '=', $user->id)->where('jobs1_id', '=', $jobs->id)->first();
    if(!empty($jobs_application))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User already applied in this job',
      ], 'back');

    $data = new JobsApplication();
    $data->user_id = $user->id;
    $data->resume_id = $user->resume[0]->id;
    $data->jobs1_id = $jobs->id;
    $data->is_approve_corp = 1;
    $data->salary_approve = $jobs->salary_casual;
    $data->salary_init = $data->salary_approve;
    $data->status = 'working';
    $data->generated_from = 'on_the_spot';
    $data->save();

    $check_log = new CheckLog();
    $check_log->jobs_application_id = $data->id;
    $check_log->jobs1_id = $data->jobs->id;
    $check_log->jobs_shift_id = $jobs_shift->id;
    $check_log->user_id = $user->id;
    $check_log->type = 'check_in';
    $check_log->date = Carbon::now();
    $check_log->date_init = Carbon::now();
    $check_log->save();

    $user->is_working = 1;
    $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function change_live(Request $request){
    $jobs = Jobs::find($request->jobs_id);
    $jobs->publish_start_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_start_date);
    $jobs->publish_end_at = Carbon::createFromFormat('d/m/Y H:i', $request->publish_end_date);
    $jobs->is_live_app = 1;
    $jobs->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs');
  }

  public function delete(Request $request){
    Jobs::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/jobs');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
