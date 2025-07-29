<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use PDF;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\Helper\Controller\HomeHelper;
use App\Http\Controllers\User\ConsultantController;

use App\Models\User;
use App\Models\Type;
use App\Models\Jobs;
use App\Models\JobsApplication;
use App\Models\JobsApprove;
use App\Models\JobsShift;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\City;

class HomeController extends BaseController{
  public function index(Request $request){
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();
    $jobs_approve_model = new JobsApprove();
    $jobs_shift_model = new JobsShift();
    $category_model = new Category();
    $sub_category_model = new SubCategory();
    $city_model = new City();
    $user_model = new User();
    $type_model = new Type();
    $response = [];

    if(Auth::user()->type->name == "staff"){
      $jobs_approve_temp = JobsApprove::select('jobs1_id')
        ->selectRaw('MAX(id) as id')
        ->groupBy('jobs1_id')
        ->where('user_id', '=', Auth::user()->id)
        ->where('status_approve', '=', 'not_yet_approved');

      $arr_jobs = Jobs::select($jobs_model->get_table_name().'.*', )
        ->join($sub_category_model->get_table_name(), $jobs_model->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
        ->join($category_model->get_table_name(), $sub_category_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id')
        ->join($user_model->get_table_name(), $jobs_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
        ->joinSub($jobs_approve_temp, $jobs_approve_model->get_table_name(), $jobs_approve_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->where($jobs_model->get_table_name().'.is_approve', '=', 0)
        ->limit(6)
        ->get();

      $total_inactive_jobs = Jobs::select($jobs_model->get_table_name().'.*', )
        // ->joinSub($jobs_approve_temp, $jobs_approve_model->get_table_name(), $jobs_approve_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        // ->where($jobs_model->get_table_name().'.is_approve', '=', 0)
        // ->where($jobs_model->get_table_name().'.created_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%')
        ->get()->count();

      $jobs_shift_temp = JobsShift::select('jobs1_id')
        ->selectRaw('MIN(id) as id')
        ->groupBy('jobs1_id')
        ->where('start_date', '<=', Carbon::now())
        ->where('end_date', '>=', Carbon::now());
      $total_active_jobs = Jobs::select($jobs_model->get_table_name().'.*', )
        ->joinSub($jobs_approve_temp, $jobs_approve_model->get_table_name(), $jobs_approve_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->joinSub($jobs_shift_temp, $jobs_shift_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        // ->where($jobs_model->get_table_name().'.is_approve', '=', 1)
        ->where($jobs_model->get_table_name().'.created_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%')
        ->get()->count();

      $total_customer_regular = User::select($user_model->get_table_name().'.*', )
        ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
        ->where($user_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->where($type_model->get_table_name().'.name', '=', 'customer_regular')
        ->where($user_model->get_table_name().'.created_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%')
        ->get()->count();
      

      $jobs_application_temp = JobsApplication::select($jobs_application_model->get_table_name().'.user_id')
        ->selectRaw('MAX('.$jobs_application_model->get_table_name().'.id) as id')
        ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->groupBy('user_id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->where($jobs_application_model->get_table_name().'.working_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%');

      $total_customer_oncall = User::select($user_model->get_table_name().'.*', )
        ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
        ->joinSub($jobs_application_temp, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
        ->where($type_model->get_table_name().'.name', '=', 'customer_oncall')
        ->get()->count();

      $response = [
        'arr_jobs' => $arr_jobs,
        'total_inactive_jobs' => $total_inactive_jobs,
        'total_active_jobs' => $total_active_jobs,
        'total_customer_regular' => $total_customer_regular,
        'total_customer_oncall' => $total_customer_oncall,
      ];
    }
    else if(Auth::user()->type->name == "RO"){
      $arr_jobs = Jobs::select($jobs_model->get_table_name().'.*', )
        ->join($sub_category_model->get_table_name(), $jobs_model->get_table_name().'.sub_category_id', '=', $sub_category_model->get_table_name().'.id')
        ->join($category_model->get_table_name(), $sub_category_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id')
        ->join($user_model->get_table_name(), $jobs_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
        // ->where($jobs_model->get_table_name().'.created_by', '=', Auth::user()->id)
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        // ->where($jobs_model->get_table_name().'.is_approve', '=', 1)
        ->limit(6)
        ->get();

      $total_active_jobs = Jobs::select($jobs_model->get_table_name().'.*', )
        // ->where($jobs_model->get_table_name().'.is_approve', '=', 1)
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        // ->where($jobs_model->get_table_name().'.created_by', '=', Auth::user()->id)
        ->where($jobs_model->get_table_name().'.created_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%')
        ->get()->count();

      $jobs_application_temp = JobsApplication::select($jobs_application_model->get_table_name().'.user_id')
        ->selectRaw('MAX('.$jobs_application_model->get_table_name().'.id) as id')
        ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->groupBy('user_id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->where($jobs_application_model->get_table_name().'.working_at', 'like', Carbon::now()->formatLocalized('%Y-%m').'%');

      $total_worker_oncall = User::select($user_model->get_table_name().'.*', )
        ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
        ->joinSub($jobs_application_temp, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
        ->orWhere($type_model->get_table_name().'.name', '=', 'customer_oncall')
        ->get()->count();

      $total_worker_regular = User::select($user_model->get_table_name().'.*', )
        ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
        // ->joinSub($jobs_application_temp, $jobs_application_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
        ->where($type_model->get_table_name().'.name', '=', 'customer_regular')
        ->where($user_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->get()->count();

      $response = [
        'arr_jobs' => $arr_jobs,
        'total_active_jobs' => $total_active_jobs,
        'total_worker_oncall' => $total_worker_oncall,
        'total_worker_regular' => $total_worker_regular,
      ];
    }

    return $this->get_data_helper->return_data($request, [], 'view', 'home.index', $response);
  }
}
